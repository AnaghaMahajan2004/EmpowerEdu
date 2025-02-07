<?php
require('razorpay-php-master/Razorpay.php');  // Ensure you include the Razorpay PHP SDK
require('src/PHPMailer.php');          // Include PHPMailer library
require('src/SMTP.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

use Razorpay\Api\Api;

// Database connection details
$servername = "localhost";
$username = "root";
$password = "anagha@2004";
$dbname = "wim_project";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Razorpay API credentials (directly provided here for your use case)
$apiKey = "rzp_test_ttn6q6uTL671p7"; // Use your Razorpay test API key
$apiSecret = "v4VskfTfNQG2fsMmWgxWdSne"; // Use your Razorpay test API secret

// Retrieve session variables
session_start();

// Capture data from POST request
$fullname = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : 'Unknown';
$email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : 'unknown@example.com';
$phone = isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '0000000000';
$plan = isset($_POST['plan']) ? htmlspecialchars($_POST['plan']) : 'Unknown';
$amount = isset($_POST['amount']) ? (int) ($_POST['amount'] * 100) : 0;

// Store in session
$_SESSION['username'] = $fullname;
$_SESSION['email'] = $email;
$_SESSION['phone'] = $phone;
$_SESSION['plan'] = $plan;
$_SESSION['amount'] = $amount;

// Function to generate OTP
function generateOtp()
{
    return rand(100000, 999999); // 6-digit OTP
}

// Send OTP via email
if (isset($_POST['sendOtp'])) {
    $otp = generateOtp();
    $_SESSION['otp'] = $otp; // Save OTP in session for verification

    $mail = new PHPMailer(true);
    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Replace with your email provider's SMTP host
        $mail->SMTPAuth = true;
        $mail->Username = 'anagharmahajan@gmail.com'; // Replace with your email
        $mail->Password = 'kwns qpbx tquf fbgo'; // Replace with your email password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Email settings
        $mail->setFrom('anagharmahajan@gmail.com', 'EmpowerEdu');
        $mail->addAddress($_SESSION['email']);
        $mail->Subject = 'Your OTP for EmpowerEdu Payment';
        $mail->Body = "Your OTP for payment is $otp. Please use this to complete your payment.";

        $mail->send();
        echo json_encode(['status' => 'success']);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $mail->ErrorInfo]);
    }
    exit();
}

if (isset($_POST['verifyOtp'])) {
    $enteredOtp = $_POST['otp'];
    error_log("Entered OTP: " . $enteredOtp);  // Debug: Check entered OTP
    error_log("Session OTP: " . $_SESSION['otp']);  // Debug: Check session OTP

    if ($enteredOtp == $_SESSION['otp']) {
        unset($_SESSION['otp']);  // Clear OTP after successful verification
        $_SESSION['otpVerified'] = true;
        $_SESSION['paymentStatus'] = 'success';
        echo json_encode(['status' => 'success', 'message' => 'Payment Done!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid OTP. Please try again.']);
    }
    exit();
}


// Initialize Razorpay API instance
$api = new Api($apiKey, $apiSecret);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Processing Payment...</title>
    <style>
        /* Basic styling for the page */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f7f6;
            background-image: url("paymentbg.jpg");
            background-size: cover;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: white;
            border-radius: 8px;
            padding: 40px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
        }

        .button-container {
            display: flex;
            flex-direction: column;
            width: 100%;
            align-items: center;
        }

        button {
            background-color: #937807;
            color: #070d3a;
            border: none;
            padding: 12px 20px;
            font-size: 20px;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            width: 70%;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #d8b318;
        }

        .otp-container {
            display: none;
            background-color: white;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 50%;
            z-index: 1000;
        }

        .otp-container input,
        .otp-container button {
            display: block;
            margin: 10px auto;
            padding: 10px;
            font-size: 16px;
        }

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Choose Your Payment Method</h1>
        <div class="button-container">
            <button id="payWithRazorpay">Pay with Razorpay</button>
            <button id="payWithOTP">Pay via OTP</button>
        </div>

        <!-- Payment success GIF and Download button -->
        <?php if (isset($_SESSION['otpVerified']) && $_SESSION['otpVerified'] === true): ?>
            <div id="paymentSuccess" style="text-align: center;">
                <img src="paymentdone.gif" alt="Payment Successful"
                    style="max-width: 100%; height: auto; padding-top: 10px;">
                <a href="download_receipt.php" class="button" style="
            display: inline-block;
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #937807;
            color: #070d3a;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            font-size: 20px;
        ">Download Receipt</a>
            </div>
        <?php endif; ?>

    </div>

    <!-- Overlay and OTP Container -->
    <div class="overlay" id="overlay" style="display: none;"></div>
    <div class="otp-container" id="otpContainer" style="display: none;">
        <h2>OTP Payment</h2>
        <p><strong>Plan:</strong> <?php echo htmlspecialchars($_SESSION['plan'] ?? 'N/A'); ?></p>
        <p><strong>Amount:</strong> ₹<?php echo number_format(($_SESSION['amount'] ?? 0) / 100, 2); ?></p>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($_SESSION['username'] ?? 'N/A'); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['email'] ?? 'N/A'); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($_SESSION['phone'] ?? 'N/A'); ?></p>

        <!-- OTP Input Form -->
        <form id="otpForm">
            <input type="email" name="email" placeholder="Enter your email" required>
            <button type="button" id="sendOtpBtn">Send OTP</button>
        </form>

        <form id="verifyOtpForm" method="POST" style="display: none;">
            <input type="number" name="otp" placeholder="Enter OTP" required>
            <button type="submit" name="verifyOtp">Verify OTP</button>
        </form>
    </div>


    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        document.getElementById("payWithRazorpay").onclick = function () {
            var options = {
                "key": "<?php echo $apiKey; ?>", // Your Razorpay Key ID
                "amount": "<?php echo $amount; ?>", // Amount in paise
                "currency": "INR",
                "name": "EmpowerEdu",
                "description": "Payment for Plan: <?php echo $plan; ?>",
                "image": "path/to/your/logo.png", // Optional
                "handler": function (response) {
                    // Handle payment success
                    alert("Payment ID: " + response.razorpay_payment_id);
                },
                "prefill": {
                    "name": "<?php echo $fullname; ?>",
                    "email": "<?php echo $email; ?>",
                    "contact": "<?php echo $phone; ?>"
                },
                "theme": {
                    "color": "#937807"
                }
            };
            var rzp1 = new Razorpay(options);
            rzp1.open();
        };



        document.getElementById("payWithOTP").onclick = function () {
            document.getElementById("otpContainer").style.display = "block";
            document.getElementById("overlay").style.display = "block";
        };

        document.getElementById("sendOtpBtn").onclick = function () {
            var formData = new FormData();
            formData.append("sendOtp", true);
            formData.append("email", document.querySelector("input[name=email]").value);

            // AJAX request to send OTP
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "payscript.php", true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.status === "success") {
                        alert("OTP Sent Successfully!");
                        document.getElementById("verifyOtpForm").style.display = "block";
                        document.getElementById("otpForm").style.display = "none";
                    } else {
                        alert("Error: " + response.message);
                    }
                }
            };
            xhr.send(formData);
        };

        document.getElementById("verifyOtpForm").onsubmit = function (event) {
            event.preventDefault();
            var otp = document.querySelector('input[name="otp"]').value;

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "payscript.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.status === 'success') {
                        alert(response.message);
                        location.reload(); // Refresh the page after successful payment
                    } else {
                        alert('Error: ' + response.message);
                    }
                }
            };
            xhr.send("verifyOtp=true&otp=" + otp);
        };
    </script>

</body>

</html>
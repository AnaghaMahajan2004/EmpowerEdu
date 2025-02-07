<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "anagha@2004";
$dbname = "wim_project";

// Include PHPMailer classes
require 'src/PHPMailer.php';
require 'src/SMTP.php';
require 'src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    // $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Generate OTPs
    $emailOtp = rand(100000, 999999);

    // Insert user details with OTPs into the database
    $sql = "INSERT INTO user (email, name, password, phone_number, email_verified, email_otp) 
            VALUES ('$email', '$fullname', '$password', '$mobile', 0, '$emailOtp')";

    if ($conn->query($sql) === TRUE) {

        $user_id = $conn->insert_id;
        $_SESSION['user_id'] = $user_id;
        // $_SESSION['username'] = $username;
        // Send OTP via email using PHPMailer
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com';                       // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'anagharmahajan@gmail.com';        // SMTP username
            $mail->Password = 'kwns qpbx tquf fbgo';              // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // Enable TLS encryption
            $mail->Port = 587;                                   // TCP port to connect to

            // Recipients
            $mail->setFrom('anagharmahajan@gmail.com', 'EmpowerEdu');
            $mail->addAddress($email);                            // Add a recipient

            // Content
            $mail->isHTML(false);                                 // Set email format to plain text
            $mail->Subject = 'Verify Your Email';
            $mail->Body    = "Your email OTP is: $emailOtp";

            // Send the email
            $mail->send();

            // Redirect to verification page
            header("Location: verify.html");
            exit;
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

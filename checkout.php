<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - EmpowerEdu</title>
    <style>
        body {
            font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
            background-color: #4e3900;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            background-image: url("checkoutbg.jpg");
            background-size: cover;
        }

        h1 {
            text-align: center;
            color: #1a0393;
            font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
        }

        .summary {
            margin-top: 20px;
        }

        .summary p {
            font-size: 20px;
            color: black;
        }

        .checkout-form {
            margin-top: 30px;
            color: black;
            font-size: 20px;
            font-weight: bold;

        }

        .check-out-img {
            display: flex;
            justify-content: center;
            margin-top: 10px;
        }

        .check-out-img img {
            padding-left: 40px;
            width: 600px;
        }

        .checkout-form input[type="text"],
        .checkout-form input[type="email"],
        .checkout-form input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            color: black;
            font-size: 20px;
        }

        .checkout-form button {
            display: block;
            width: 100%;
            background: black;
            color: #fff200;
            border: none;
            padding: 10px;
            font-size: 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .checkout-form button:hover {
            background: #3b3636;
        }
    </style>
</head>

<body>
    <div class="container">
        <?php
        // Capture the query parameters from the URL
        $plan = isset($_GET['plan']) ? htmlspecialchars($_GET['plan']) : "Unknown Plan";
        $amount = isset($_GET['amount']) ? (float) $_GET['amount'] : 0;

        // Plan details to display dynamically
        echo '<a class="check-out-img"><img src="checkout.gif"/></a>';
        echo "<div class='summary'>";
        echo "<p><strong>Selected Plan:</strong> " . ucfirst($plan) . "</p>";
        echo "<p><strong>Amount:</strong> ₹" . number_format($amount, 2, '.', '') . "</p>";
        echo "</div>";

        // if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // $username = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
        // $email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';
        // $phone = isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '';

        session_start();
        // $_SESSION['username'] = $username;
        // $_SESSION['email'] = $email;
        // $_SESSION['phone'] = $phone;
        // $_SESSION['plan'] = $plan;
        // $_SESSION['amount'] = $amount;
        // }
        ?>

        <!-- Checkout Form -->
        <form class="checkout-form" action="payscript.php" method="POST">
            <input type="hidden" name="plan" value="<?php echo $plan; ?>">
            <input type="hidden" name="amount" value="<?php echo $amount; ?>">

            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" placeholder="Enter your full name" required>

            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" placeholder="Enter your email address" required>

            <label for="phone">Phone Number</label>
            <input type="number" id="phone" name="phone" placeholder="Enter your phone number" required>

            <button type="submit">Proceed to Payment</button>
        </form>
    </div>
</body>

</html>
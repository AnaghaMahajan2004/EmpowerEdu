<?php
// Start session to manage user login state
session_start();

// Database connection settings
$servername = "localhost";
$username = "root";
$password = "anagha@2004"; // Your database password
$dbname = "wim_project";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the email and password from the POST request
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Sanitize the input to prevent SQL injection
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);

    // Query the database for the user with the given email
    $query = "SELECT * FROM user WHERE email = '$email' LIMIT 1";
    $result = mysqli_query($conn, $query);

    // Check if the email exists
    if (mysqli_num_rows($result) > 0) {
        // Fetch the user data
        $user = mysqli_fetch_assoc($result);

        // Verify the password (assuming it is hashed in the database)
        if (password_verify($password, $user['password'])) {
            // Password is correct, check if the email is verified
            if ($user['email_verified'] == 1) {
                // Start the session for the user and store the user ID in session
                $_SESSION['userid'] = $user['userid']; // Store the user ID in session
                header("Location: RegHomePage.html"); // Redirect to profile page
                exit();
            } else {
                // Email is not verified
                echo "Please verify your email first.";
            }
        } else {
            // Invalid password
            echo "Invalid credentials. Please try again.";
        }
    } else {
        // Email not found in the database
        echo "Invalid credentials. Please try again.";
    }
}

// Close database connection
mysqli_close($conn);
?>

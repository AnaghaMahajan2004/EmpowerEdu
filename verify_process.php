<?php
// Start the session to access session data
session_start();

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

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $otp = $_POST['otp'];
    $verify_type = $_POST['verify_type'];  // It will be 'email' now

    // Fetch the user_id from the session
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];  // Get user_id from the session
    } else {
        // If user_id is not set in session, handle the error (e.g., redirect to login page)
        die("User is not logged in.");
    }

    // SQL query to get OTP values for email
    $sql = "SELECT email_otp, email_verified FROM user WHERE userid = $user_id";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        if ($verify_type == 'email') {
            // Check if email OTP is correct and not verified yet
            if ($otp == $row['email_otp'] && $row['email_verified'] == 0) {
                // Update email verification status
                $update_sql = "UPDATE user SET email_verified = 1 WHERE userid = $user_id";
                if ($conn->query($update_sql) === TRUE) {
                    header("Location: RegHomePage.html");
                } else {
                    echo "Error updating record: " . $conn->error;
                }
            } else {
                echo "Invalid or already verified email OTP.";
            }
        }
    } else {
        echo "User not found.";
    }

    // Close connection
    $conn->close();
}
?>

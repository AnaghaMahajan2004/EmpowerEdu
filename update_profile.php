<?php
// This file handles the updating of the profile fields

session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit;
}

// Get logged-in user ID
$user_id = $_SESSION['user_id'];

// Include database connection
$servername = "localhost";
$username = "root";
$password = "anagha@2004";
$dbname = "wim_project";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle the profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $field = $_POST['field']; // The field to be updated
    $new_value = $_POST['value']; // The new value for the field

    // Update the field in the profile table
    $update_query = "UPDATE user SET $field = ? WHERE userid = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("si", $new_value, $user_id);
    $stmt->execute();

    // Redirect back to the profile page
    header("Location: profilefront.php");
}

$conn->close(); // Close the database connection
?>

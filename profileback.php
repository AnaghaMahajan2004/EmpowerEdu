<?php
session_start();

// Database connection details
$servername = "localhost";
$username = "root";
$password = "anagha@2004";
$dbname = "wim_project";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the request is valid
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['field']) && isset($_POST['new_value'])) {
    $field = $_POST['field'];
    $new_value = $_POST['new_value'];
    $userid = $_SESSION['userid'];

    // Validate the field to prevent SQL injection
    $allowed_fields = ['name', 'email', 'phone_number'];
    if (in_array($field, $allowed_fields)) {
        $sql = "UPDATE user SET $field = ? WHERE userid = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', $new_value, $userid);

        if ($stmt->execute()) {
            echo "Success";
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Invalid field.";
    }
}

$conn->close();
?>

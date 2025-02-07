<?php
session_start();

// Database connection details
$servername = "localhost";
$username = "root";
$password = "anagha@2004"; // Replace with your database password
$dbname = "wim_project"; // Replace with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is logged in
if (!isset($_SESSION['userid'])) {
    header('Location: login.html');  // Redirect to login page if not logged in
    exit();
}

$userid = $_SESSION['userid'];
$field = isset($_GET['field']) ? $_GET['field'] : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Process the form submission
    $new_value = $_POST['new_value'];

    // Update the database based on the field
    if ($field == 'name') {
        $sql = "UPDATE user SET name = ? WHERE userid = ?";
    } elseif ($field == 'email') {
        $sql = "UPDATE user SET email = ? WHERE userid = ?";
    } elseif ($field == 'phone_number') {
        $sql = "UPDATE user SET phone_number = ? WHERE userid = ?";
    } elseif ($field == 'email_verified') {
        $sql = "UPDATE user SET email_verified = ? WHERE userid = ?";
    }

    if (isset($sql)) {
        $stmt = $conn->prepare($sql);
        if ($field == 'email_verified') {
            $stmt->bind_param('ii', $new_value, $userid); // email_verified is a tinyint
        } else {
            $stmt->bind_param('si', $new_value, $userid);
        }

        if ($stmt->execute()) {
            echo "Profile updated successfully.";
            header('Location: profilefront.php');
            exit();
        } else {
            echo "Error updating profile: " . $conn->error;
        }
    }
}

// Fetch the current value for the field
$sql = "SELECT $field FROM user WHERE userid = $userid";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $current_value = $row[$field];
} else {
    echo "No profile found.";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
</head>
<body>
    <h2>Edit Your Profile</h2>

    <form method="POST">
        <label for="new_value">New Value:</label>
        <input type="text" name="new_value" id="new_value" value="<?php echo isset($current_value) ? $current_value : ''; ?>" required>
        <br>
        <input type="submit" value="Save">
    </form>

    <a href="profilefront.php">Back to Profile</a>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>

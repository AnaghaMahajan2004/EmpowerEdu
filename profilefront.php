<?php
// Start the session
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

// Fetch user data based on the logged-in user ID
$userid = $_SESSION['userid']; // Get the logged-in user ID from session

$sql = "SELECT * FROM user WHERE userid = $userid";
$result = $conn->query($sql);

// Check if the user data exists
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    echo "No profile found.";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Profile</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            /* Full viewport height */
            font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
            background: #554900;
            /* Light blue background */
            padding-top: 70px;

        }

        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #d0bb02;
            color: white;
            padding-left: 0;
            padding-right: 10px;
            padding-bottom: 0;
            width: 100%;
            height: 60px;
            position: fixed;
            /* Fixed position */
            top: 0;
            /* Stick to the top */
            z-index: 1000;
        }

        header nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            padding: 0;
            margin: 0;
        }

        .logo {
            display: flex;
            align-items: center;
            margin-right: 20px;
        }

        .logo img {
            height: 65px;
        }

        .nav-links {
            list-style: none;
            display: flex;
            /* gap: 10px; */
            margin: 0;
            flex-grow: 1;
            align-items: center;
        }

        .nav-links li {
            flex: 1;
            /* Ensures equal spacing between links */
            text-align: center;
            /* Center aligns each link in its space */
            width: 100%;
            line-height: 60px;
            /* Adjust line height to match the height of the header */
            height: 60px;
        }

        .nav-links a {
            color: rgb(20, 9, 103);
            text-decoration: none;
            font-size: 25px;
            font-weight: bold;
            transition: color 0.3s;
            display: block;
            /* Make the anchor tag block-level to fill the li and allow centering */
            height: 100%;
            /* Ensure the anchor fills the li vertically */
            line-height: 60px;
        }

        .nav-links a:hover {
            color: rgb(7, 47, 155);
        }

        .btn {
            background: #dfc637;
            border-radius: 4px;
            color: white;
            text-decoration: none;
            font-weight: bold;
            height: 100%;
            /* Ensure button fills the li element vertically */
            line-height: 60px;
            /* Center text vertically inside the button */
            display: inline-flex;
            /* Align the button properly */
            justify-content: center;
            /* Center text horizontally */
            align-items: center;
            /* Center text vertically */
            /* margin-left: 20px; */
        }

        .container {
            background: #fff;
            /* White background for contrast */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: left;
            /* Align text inside container */
            max-width: 600px;
            width: 100%;
            /* Responsive width */
            height: 500px;
            background-image: url("checkoutbg.jpg");
            background-size: cover;
        }

        .container p {
            font-size: 30px;
            margin: 10px 0;
            color: #0e114a;
        }

        .container a {
            font-size: 20px;
            color: rgb(20, 9, 103);
            text-decoration: none;
            margin-left: 5px;
            font-weight: bold;
        }

        .container a:hover {
            text-decoration: underline;
        }

        .profile-img {
            width: 300px;
            padding-bottom: 15px;
        }
        .editable-field {
            margin-bottom: 15px;
        }

        .save-button {
            margin-left: 10px;
            padding: 5px 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .save-button:hover {
            background-color: #0056b3;
        }
        

    </style>

    <script>
        function enableEdit(field) {
            const valueSpan = document.getElementById(`${field}-value`);
            const inputBox = document.getElementById(`${field}-input`);
            const saveButton = document.getElementById(`${field}-save`);

            valueSpan.style.display = "none";
            inputBox.style.display = "inline-block";
            saveButton.style.display = "inline-block";
        }

        function saveEdit(field) {
            const newValue = document.getElementById(`${field}-input`).value;

            // Send AJAX request to update value in the database
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "profileback.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Update the value in the UI
                    document.getElementById(`${field}-value`).textContent = newValue;
                    document.getElementById(`${field}-value`).style.display = "inline-block";
                    document.getElementById(`${field}-input`).style.display = "none";
                    document.getElementById(`${field}-save`).style.display = "none";
                }
            };
            xhr.send(`field=${field}&new_value=${encodeURIComponent(newValue)}`);
        }
    </script>
</head>

<body>
    <header>
        <nav>
            <div class="logo">
                <img src="Logo.gif" alt="EmpowerEdu Logo" />
            </div>
            <ul class="nav-links">
                <li><a href="HomePage.html">ʜᴏᴍᴇ</a></li>
                <li><a href="courses.html">ᴄᴏᴜʀꜱᴇꜱ</a></li>
                <li><a href="pricing.html">ᴘʀɪᴄɪɴɢ</a></li>
                <li><a href="#about">ᴀʙᴏᴜᴛ ᴜꜱ</a></li>
                <li><a href="#contact">ᴄᴏɴᴛᴀᴄᴛ</a></li>
                <li><a href="logout.php" class="btn">ʟᴏɢ ᴏᴜᴛ</a></li>
            </ul>
        </nav>
    </header>
    <!-- <h2>Your Profile</h2> -->
    <img src="profileheading.gif" class="profile-img">

    <div class="container">
    <p>
    𝑵𝒂𝒎𝒆: 
    <span id="name-value"><?php echo isset($row['name']) ? $row['name'] : 'Not Available'; ?></span>
    <input id="name-input" type="text" style="display: none;" value="<?php echo isset($row['name']) ? $row['name'] : ''; ?>">
    <button id="name-save" class="save-button" style="display: none;" onclick="saveEdit('name')">Save</button>
    <a href="javascript:void(0);" onclick="enableEdit('name')">Edit</a>
</p>

<p>
    𝑬𝒎𝒂𝒊𝒍: 
    <span id="email-value"><?php echo isset($row['email']) ? $row['email'] : 'Not Available'; ?></span>
    <input id="email-input" type="text" style="display: none;" value="<?php echo isset($row['email']) ? $row['email'] : ''; ?>">
    <button id="email-save" class="save-button" style="display: none;" onclick="saveEdit('email')">Save</button>
    <a href="javascript:void(0);" onclick="enableEdit('email')">Edit</a>
</p>

<p>
    𝑷𝒉𝒐𝒏𝒆 𝑵𝒖𝒎𝒃𝒆𝒓: 
    <span id="phone_number-value"><?php echo isset($row['phone_number']) ? $row['phone_number'] : 'Not Available'; ?></span>
    <input id="phone_number-input" type="text" style="display: none;" value="<?php echo isset($row['phone_number']) ? $row['phone_number'] : ''; ?>">
    <button id="phone_number-save" class="save-button" style="display: none;" onclick="saveEdit('phone_number')">Save</button>
    <a href="javascript:void(0);" onclick="enableEdit('phone_number')">Edit</a>
</p>

<p>
    𝑬𝒎𝒂𝒊𝒍 𝑽𝒆𝒓𝒊𝒇𝒊𝒆𝒅:
    <span id="email_verified-value"><?php echo isset($row['email_verified']) ? ($row['email_verified'] == 1 ? 'Yes' : 'No') : 'Not Available'; ?></span>
    <input id="email_verified-input" type="text" style="display: none;" value="<?php echo isset($row['email_verified']) ? $row['email_verified'] : ''; ?>">
</p>

    </div>



</body>

</html>

<?php
// Close the database connection
$conn->close();
?>
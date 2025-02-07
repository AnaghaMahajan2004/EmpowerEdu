<?php
// Include database connection (update the path based on your project structure)

$servername = "localhost";
$username = "root";
$password = "anagha@2004";
$dbname = "wim_project";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


// SQL query to fetch members' data
$query = "SELECT name, userid, phone_number, email FROM user";
$result = mysqli_query($conn, $query);

// Check if any records were returned
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>View Members - EmpowerEdu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #2c3e50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #ddd;
        }
        .back-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .back-btn:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>

    <h1>View Members</h1>

    <!-- Table to display members -->
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>User ID</th>
                <th>Phone Number</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch and display each member
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['userid']) . "</td>";
                echo "<td>" . htmlspecialchars($row['phone_number']) . "</td>";
                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Back Button -->
    <a href="about.html" class="back-btn">Back to About Us</a>

</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<body>

<h1>My First Heading</h1>

<p>My first paragraph.</p>

</body>
</html>

<?php
$servername = "mysqlms.mysql.database.azure.com";
$username = "superuser";
$password = "Password123";
$dbname = "noise_monitoring";

echo "Hello from PHP script";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Insert data into database
$sql_quiry = "INSERT INTO measurements (measurementUnit_id, acousticShocks, timeLeft) VALUES (1, 4, 6.3)";

if ($conn->query($sql_quiry) == TRUE) {
    echo "New record created succesfully";
}
else
{
    echo "Error: " . $sql . "<br>" . $conn->error;
}
$conn->close();
?>
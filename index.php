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
$sql_quiry = "INSERT INTO measurements (measurementUnit_id, acousticShocks, hoursLeft) VALUES (1, 4, 6.3)";

if ($conn->query($sql_quiry) == TRUE) {
    echo "New record created succesfully";
}
else
{
    echo "Error: " . $sql . "<br>" . $conn->error;
}
$conn->close();
?>
<?php
// database constants
$servername = "mysqlms.mysql.database.azure.com";
$username = "superuser";
$password = "Password123";
$dbname = "noise_monitoring";

// collects variables sent from http request
$measurementUnit_id = $_GET["measurementUnit_id"];
$acousticShocks = $_GET["acousticShocks"];
$timeLeft = $_GET["timeLeft"];

// if not null
if (isset($measurementUnit_id, $acousticShocks, $timeLeft)) {

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // creating query
    $sql_quiry = "INSERT INTO measurements (measurementUnit_id, acousticShocks, timeLeft) VALUES ($measurementUnit_id, $acousticShocks, $timeLeft)";

    // upload successful?
    if ($conn->query($sql_quiry) == TRUE) {
        echo "New measurement inserted succesfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // break connection
    $conn->close();
} else {
    echo "No or missing data has been sent...";
}

?>
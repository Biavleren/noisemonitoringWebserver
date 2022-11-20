<?php
// database constants
$servername = "mysqlms.mysql.database.azure.com";
$username = "superuser";
$password = "Password123";
$dbname = "noise_monitoring";

// collects variables sent from http request
$measurementUnit_serialNum = $_POST["measurementUnit_serialNum"];
$acousticShocks = $_POST["acousticShocks"];
$spl_array = $_POST["spl_array"]; // PHP automatically detects as array


echo "measurementUnit_serialNum: ".$measurementUnit_serialNum;
echo "acousticShocks: ".$acousticShocks;
echo "spl_array[0]: ".$spl_array[0];
echo "spl_array[1]: ".$spl_array[1];
echo "spl_array[2]: ".$spl_array[2];

// if not null, proceed
if (isset($measurementUnit_serialNum, $acousticShocks, $spl_array)) {

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // sql query
    $sql_query = "INSERT INTO soundPressureLevelRaw";
    $sql_query .= " (spl0, spl1, spl2)";
    $sql_query .= " VALUES ($spl_array[0], $spl_array[1], $spl_array[2]);";
    $sql_query .= " INSERT INTO measurements";
    $sql_query .= " (measurementUnit_serialNum, soundPressureLevelRaw_id, employee_id, acousticShocks)";
    $sql_query .= " VALUES ($measurementUnit_serialNum, LAST_INSERT_ID(),";
    $sql_query .= " (SELECT employee_id FROM measurementUnit_users WHERE measurementUnit_serialNum = $measurementUnit_serialNum), $acousticShocks);";

    // check for success
    if ($conn->multi_query($sql_query) == TRUE) {
        echo "New record inserted succesfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // break connection
    $conn->close();
}
else {
    echo "No data has been sent...";
}

?>
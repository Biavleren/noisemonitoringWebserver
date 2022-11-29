<?php
// database constants
$servername = "mysqlms.mysql.database.azure.com";
$username = "superuser";
$password = "Password123";
$dbname = "noise_monitoring";

// collects variables sent from http request
$measurementUnit_serialNum = $_POST["measurementUnit_serialNum"];
$acousticShocks = isset($_POST["acousticShocks"]) ? $_POST["acousticShocks"] : 0;
$spl_length = isset($_POST["spl_length"]) ? $_POST["spl_length"] : 0;

$spl_array = array();

for ($c = 0; $c < $spl_length; $c++)
{
    array_push($spl_array, $_POST["spl_array$c"]);
}

// $spl_array0 = $_POST["spl_array0"];
// $spl_array1 = $_POST["spl_array1"];
// $spl_array2 = $_POST["spl_array2"];
// $spl_array3 = $_POST["spl_array3"];
// $spl_array4 = $_POST["spl_array4"];
// $spl_array5 = $_POST["spl_array5"];
// $spl_array6 = $_POST["spl_array6"];
// $spl_array7 = $_POST["spl_array7"];
// $spl_array8 = $_POST["spl_array8"];
// $spl_array9 = $_POST["spl_array9"];

echo "measurementUnit_serialNum: ".$measurementUnit_serialNum;
echo "  acousticShocks: ".$acousticShocks;
echo "  spl_length: ".$spl_length;
for ($j = 0; $j < $spl_length; $j++) {
    echo "  spl_array[$j]: ".$spl_array[$j];
  }

// if not null, proceed
if (isset($measurementUnit_serialNum)) {

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // sql query
    /*
    $sql_query = "INSERT INTO soundPressureLevelRaw";
    $sql_query .= " (spl0, spl1, spl2)";
    $sql_query .= " VALUES ($spl_array[0], $spl_array[1], $spl_array[2]);";
    $sql_query .= " INSERT INTO measurements";
    $sql_query .= " (measurementUnit_serialNum, soundPressureLevelRaw_id, employee_id, acousticShocks)";
    $sql_query .= " VALUES ($measurementUnit_serialNum, LAST_INSERT_ID(),";
    $sql_query .= " (SELECT employee_id FROM measurementUnit_users WHERE measurementUnit_serialNum = $measurementUnit_serialNum), $acousticShocks);";
    */

    $sql_query = "INSERT INTO soundPressureLevelRaw (";
    for ($x = 0; $x < $spl_length; $x++) {
        $sql_query .= "spl$x";
        if (($spl_length-$x) > 1)
        {
            $sql_query .= ", ";
        }
    }
    $sql_query .= ") VALUES (";
    for ($y = 0; $y < $spl_length; $y++) {
        $sql_query .= "$spl_array$y";
        if (($spl_length-$y) > 1)
        {
            $sql_query .= ", ";
        }
    }
    $sql_query .= ");";
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
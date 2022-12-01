<?php
// database constants
$servername = "mysqlms.mysql.database.azure.com";
$username = "superuser";
$password = "Password123";
$dbname = "noise_monitoring";

// collects base data into variables sent from http POST-request
$measurementUnit_serialNum = $_POST["measurementUnit_serialNum"];
$acousticShocks = isset($_POST["acousticShocks"]) ? $_POST["acousticShocks"] : 0;
$dosisLoss = isset($_POST["dosisLoss"]) ? $_POST["dosisLoss"] : 0;
$spl_length = isset($_POST["spl_length"]) ? $_POST["spl_length"] : 0;

//contstants
$spl_slow_setting = 1; // slow mode

// declaring array
$spl_array = array();

// looping through the size of SPL
for ($c = 0; $c < $spl_length; $c++) 
{
    array_push($spl_array, $_POST["spl_array$c"]);
}

// ****************** dosis calculation SPL time interval

// Get some value from db;
// $dosis = getDosisDB(); // get current dosis from DB
// *********************

// $splTimeInterval = 0.125; //tiem inteva for each spl value
// $aCoef = 2705659852;
// $bCoef = -0.231;
// $sum = 0;

// $dosisLoss = 0;
// $exposureTime = 0;
// for ($c = 0; $c < $spl_length; $c++) 
// {
//     $exposureTime += ($aCoef * exp($bCoef * ($spl_array[$c])));
//     $dosisLoss = $splTimeInterval / $exposureTime;
//     $dosis += (-$dosisLoss);
//     $hoursLeftAtCurentSPL += $exposureTime * $_dosis;
// }

// $hoursLeftAtCurentSPL = $hoursLeftAtCurentSPL/$spl_length;


//** 
// push(dosis)
// push(hoursLeftAtCurentSPL)
//*

echo "measurementUnit_serialNum: ".$measurementUnit_serialNum;
echo "\nacousticShocks: ".$acousticShocks;
echo "\ndosisLoss: ".$dosisLoss;
echo "\nspl_length: ".$spl_length;
for ($j = 0; $j < $spl_length; $j++) {
    echo "\n \tspl_array[$j]: ".$spl_array[$j];
  }

// if not null, proceed
if (isset($measurementUnit_serialNum)) {

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    
    // Building SQL query for soundPressureLevelRaw table
    $sql_query = "INSERT INTO soundPressureLevelRaw (spl_length, ";
    for ($x = 0; $x < $spl_length; $x++) {
        $sql_query .= "spl$x";
        if (($spl_length-$x) > 1)
        {
            $sql_query .= ", ";
        }
    }
    $sql_query .= ") VALUES ($spl_length, ";
    for ($y = 0; $y < $spl_length; $y++) {
        $sql_query .= "$spl_array[$y]";
        if (($spl_length-$y) > 1)
        {
            $sql_query .= ", ";
        }
    }
    $sql_query .= ");";

    // OLD Building SQL query for measurements table
    // $sql_query .= " INSERT INTO measurements";
    // $sql_query .= " (measurementUnit_serialNum, soundPressureLevelRaw_id, employee_id, acousticShocks)";
    // $sql_query .= " VALUES ($measurementUnit_serialNum, LAST_INSERT_ID(),";
    // $sql_query .= " (SELECT employee_id FROM measurementUnit_users WHERE measurementUnit_serialNum = $measurementUnit_serialNum), $acousticShocks);";


    //Building SQL query for measurements table:
    $sql_query .= " INSERT INTO measurements";
    $sql_query .= " (measurementUnit_serialNum, soundPressureLevelRaw_id, employee_id, acousticShocks, current_dosis, estimated_hoursleft)";
    $sql_query .= " SELECT $measurementUnit_serialNum, LAST_INSERT_ID(),";
    $sql_query .= " (SELECT employee_id FROM measurementUnit_users WHERE measurementUnit_serialNum = $measurementUnit_serialNum),";
    $sql_query .= " $acousticShocks,";
    $sql_query .= " ((SELECT current_dosis FROM measurements WHERE current_dosis IS NOT NULL ORDER BY id DESC LIMIT 1)-$dosisLoss)"; //calculation of current_dosis
    $sql_query .= " (((SELECT current_dosis FROM measurements WHERE current_dosis IS NOT NULL ORDER BY id DESC LIMIT 1)-$dosisLoss)*($spl_slow_setting*$spl_length/$dosisLoss));"; //calculation of estimated hoursleft

    // check for success
    if ($conn->multi_query($sql_query) == TRUE) {
        echo "\nNew record inserted succesfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // break connection
    $conn->close();
}
else {
    echo "\nNo data has been sent...";
}
?>
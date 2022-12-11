<?php
$servername = "mysqlms.mysql.database.azure.com";
$username = "superuser";
$password = "Password123";
$dbname = "noise_monitoring";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// drop table measurement
$sql_query = "DROP TABLE measurements;";

// drop table rawspl values
$sql_query .= " DROP TABLE soundPressureLevelRaw;";

// create soundpressurelevelraw
$sql_query .= " CREATE TABLE soundPressureLevelRaw (id INT NOT NULL AUTO_INCREMENT, spl_length INT UNSIGNED DEFAULT 0,";
for ($x = 0; $x < 20; $x++) {
    $sql_query .= "spl$x FLOAT UNSIGNED,";
}
$sql_query .= " PRIMARY KEY (id));";

// create measurement table
$sql_query .= " CREATE TABLE measurements (";
$sql_query .= "id INT NOT NULL AUTO_INCREMENT,";
$sql_query .= " measurementUnit_serialNum INT NOT NULL,";
$sql_query .= " soundPressureLevelRaw_id INT UNIQUE NOT NULL,";
$sql_query .= " employee_id INT,";                
$sql_query .= " acousticShocks INT UNSIGNED,";                
$sql_query .= " current_dosis FLOAT,";                
$sql_query .= " estimated_hoursleft FLOAT UNSIGNED,";
$sql_query .= " timeStamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,";                
$sql_query .= " /*Constraints*/";
$sql_query .= " PRIMARY KEY (id),";                
$sql_query .= " FOREIGN KEY (measurementUnit_serialNum)";                
$sql_query .= " REFERENCES measurementUnit(serialNum),";                
$sql_query .= " FOREIGN KEY (soundPressureLevelRaw_id)";
$sql_query .= " REFERENCES soundPressureLevelRaw(id),";
$sql_query .= " FOREIGN KEY (employee_id)";
$sql_query .= " REFERENCES employee(id));";		            

// insert spl raw sample
$sql_query .= " INSERT INTO soundPressureLevelRaw (";
$sql_query .= " spl_length, spl0, spl1, spl2) VALUES (3, 85.0, 85.0, 85.0);";

// insert measurement
$sql_query .= " INSERT INTO measurements (";
$sql_query .= " measurementUnit_serialNum,";
$sql_query .= " soundPressureLevelRaw_id,";
$sql_query .= " employee_id,";
$sql_query .= " acousticShocks,";
$sql_query .= " current_dosis,";
$sql_query .= " estimated_hoursleft)";
$sql_query .= " VALUES (";
$sql_query .= " 1, LAST_INSERT_ID(), 1, 0, 1, 24);";

if ($conn->multi_query($sql_query) == TRUE) {
    echo "\nMeasurement table cleared\n";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// break connection
$conn->close();

?>
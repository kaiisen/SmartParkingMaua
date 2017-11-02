<?php

# Receive post data
$body = file_get_contents('php://input');
$body = trim($body);
$obj = json_decode($body, true);

# Set variables to post data
$timestamp = $obj['timestamp'];
$action = $obj['action'];
$gate = $obj['gate'];

#var_dump($obj);

# Convert post variables to DB format
if ($action == "entrance") {
    $action = 1;
} elseif ($action == "exit") {
    $action = 0;
}

if ($gate == "main") {
    $gate = 1;
} elseif ($gate == "secondary") {
    $gate = 2;
}

# Set DB credentials
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'spm';
$dbtable = 'tbParking';

# Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

# Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

# Create query
$sql = "INSERT INTO $dbtable (timestamp, action, gate)
VALUES (FROM_UNIXTIME($timestamp), $action, $gate)";

#echo $sql;

# Check query
if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

# Close connection
$conn->close();

?>

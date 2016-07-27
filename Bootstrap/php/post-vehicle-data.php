<?php

// include database log in details
include "database-details.php";

// Create connection
$conn = new mysqli($server, $user, $pass, $dbname)
    or die("Connection failed: " . $conn->connect_error);

// Make SQL request
$sql = "INSERT INTO vehicles
VALUES ('". $_POST["input-regMark"] .
    "', '". $_POST["input-make"] .
    "', '". $_POST["input-validTax"] .
    "', '". $_POST["input-dataOfLiability"] .
    "', '". $_POST["input-validMot"] .
    "', '". $_POST["input-motExpiryDt"] .
    "', '". $_POST["input-firstRegDt"] .
    "', '". $_POST["input-ManufactureYr"] .
    "', '". $_POST["input-cylinderCapacity"] .
    "', '". $_POST["input-co2Emissions"] .
    "', '". $_POST["input-fuelType"] .
    "', '". $_POST["input-status"] .
    "', '". $_POST["input-colour"] .
    "', '". $_POST["input-typeApproval"] .
    "', '". $_POST["input-wheelplan"] .
    "', '". $_POST["input-revenueWeight"] ."')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();

?>

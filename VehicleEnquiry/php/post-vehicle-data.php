<?php

// include database log in details
$path = $_SERVER['DOCUMENT_ROOT'];
$path .= '/test/dbconfig.php';
include $path;

// Make SQL request
$sql = "INSERT INTO vehicles
VALUES ('".$_GET['input-regMark'].
    "', '".$_GET['input-make'].
    "', '".$_GET['input-validTax'].
    "', '".$_GET['input-dataOfLiability'].
    "', '".$_GET['input-validMot'].
    "', '".$_GET['input-motExpiryDt'].
    "', '".$_GET['input-firstRegDt'].
    "', '".$_GET['input-ManufactureYr'].
    "', '".$_GET['input-cylinderCapacity'].
    "', '".$_GET['input-co2Emissions'].
    "', '".$_GET['input-fuelType'].
    "', '".$_GET['input-status'].
    "', '".$_GET['input-colour'].
    "', '".$_GET['input-typeApproval'].
    "', '".$_GET['input-wheelplan'].
    "', '".$_GET['input-revenueWeight']."')";

if ($dbconfig->query($sql) === true) {
    echo 'New record created successfully';
} else {
    echo 'Error: '.$sql.'<br>'.$dbconfig->error;
}

$dbconfig->close();

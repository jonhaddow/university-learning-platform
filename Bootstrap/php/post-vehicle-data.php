<?php

// include database log in details
$path = $_SERVER['DOCUMENT_ROOT'];
$path .= '/test/dbconfig.php';
include $path;

// Make SQL request
$sql = "INSERT INTO vehicles
VALUES ('".$_POST['input-regMark'].
    "', '".$_POST['input-make'].
    "', '".$_POST['input-validTax'].
    "', '".$_POST['input-dataOfLiability'].
    "', '".$_POST['input-validMot'].
    "', '".$_POST['input-motExpiryDt'].
    "', '".$_POST['input-firstRegDt'].
    "', '".$_POST['input-ManufactureYr'].
    "', '".$_POST['input-cylinderCapacity'].
    "', '".$_POST['input-co2Emissions'].
    "', '".$_POST['input-fuelType'].
    "', '".$_POST['input-status'].
    "', '".$_POST['input-colour'].
    "', '".$_POST['input-typeApproval'].
    "', '".$_POST['input-wheelplan'].
    "', '".$_POST['input-revenueWeight']."')";

if ($dbconfig->query($sql) === true) {
    echo 'New record created successfully';
} else {
    echo 'Error: '.$sql.'<br>'.$dbconfig->error;
}

$dbconfig->close();

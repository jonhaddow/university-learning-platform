<?php

// include database log in details
$path = $_SERVER['DOCUMENT_ROOT'];
$path .= '/test/dbconfig.php';
include $path;

// Check if URL parameters are given.
if (!(isset($_GET['inputVrn']) && isset($_GET['inputMake']))) {

    // Make SQL Request
    $sql = 'SHOW columns FROM vehicles';
    $result = mysqli_query($dbconfig, $sql);

    $resultArray = array();

    $numOfRows = mysqli_num_rows($result);

    for ($x = 0; $x < $numOfRows; ++$x) {
        $row = mysqli_fetch_array($result);
        $resultArray[$x] = $row['Field'];
    }

    echo json_encode($resultArray);
} else {

    // Get URL parameters
    $vrn = $_GET['inputVrn'];
    $make = $_GET['inputMake'];

    // Make SQL Request
    $sql = 'SELECT * FROM vehicles '.
        'WHERE regMark = "'.$vrn.
        '" AND make = "'.$make.'"';
    $result = mysqli_query($dbconfig, $sql);

    if (mysqli_num_rows($result) > 0) {
        $resultArray = array();

        // Output data of each row
        while ($row = mysqli_fetch_assoc($result)) {
            foreach ($row as $key => $val) {
                $resultArray[$key] = $val;
            }
        }

        // Print json string as response.
        echo json_encode($resultArray);
    } else {
        echo 'Vehicle does not exist.';
    }
}

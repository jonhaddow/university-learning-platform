<?php

// include database log in details
include "database-details.php";

// Connect to mySql
$conn = mysqli_connect($server, $user, $pass, $dbname)
    or die("Connection failed: ".mysqli_error($conn));

// Check if URL parameters are given.
if (!(isset($_GET["inputVrn"]) && isset($_GET["inputMake"]))) {

    // Make SQL Request
    $sql = "SHOW columns FROM vehicles";
    $result = mysqli_query($conn, $sql);

    $resultArray = array();

    $numOfRows = mysqli_num_rows($result);

    for ($x = 0; $x < $numOfRows; $x++) {
        $row = mysqli_fetch_array($result);
        $resultArray[$x] = $row['Field'];
    }

    echo json_encode($resultArray);

} else {

    // Get URL parameters
    $vrn = $_GET["inputVrn"];
    $make = $_GET["inputMake"];

    // Make SQL Request
    $sql = "SELECT * FROM vehicles " .
        "WHERE regMark = \"" . $vrn .
        "\" AND make = \"" . $make . "\"" ;
    $result = mysqli_query($conn, $sql);


    if (mysqli_num_rows($result) > 0) {

        $resultArray = array();

        // Output data of each row
        while($row = mysqli_fetch_assoc($result)) {
            foreach ($row as $key => $val) {
                $resultArray[$key] =$val;
            }
        }

        // Print json string as response.
        echo json_encode($resultArray);

    } else {
        echo "Vehicle does not exist.";
    }
}



?>

<?php

// include database log in details
include 'DatabaseDetails.php';

// Connect to mySql
$conn = mysqli_connect($server, $user, $pass, $dbname)
    or die('Connection failed: '.mysqli_error($conn));

// Make SQL Request
$sql = 'SELECT * FROM messages ORDER BY messageID DESC LIMIT 1';
$result = mysqli_query($conn, $sql);

// Return string
echo json_encode(mysqli_fetch_object($result));

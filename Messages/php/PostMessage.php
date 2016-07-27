<?php

// include database log in details
include 'DatabaseDetails.php';

// Connect to mySql
$conn = mysqli_connect($server, $user, $pass, $dbname)
    or die('Connection failed: '.mysqli_error($conn));

// Get post text
$message = $_POST['messageContent'];

// Make SQL Request
$sql = "INSERT INTO messages (messageContent) VALUES ('".$message."')";
$result = mysqli_query($conn, $sql);

// Return string
echo 'Successful sent '.$message;

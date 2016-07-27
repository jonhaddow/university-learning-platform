<?php

// include database log in details
$path = $_SERVER['DOCUMENT_ROOT'];
$path .= '/test/dbconfig.php';
include $path;

// Get post text
$message = $_POST['messageContent'];

// Make SQL Request
$sql = "INSERT INTO messages (messageContent) VALUES ('".$message."')";
$result = mysqli_query($dbconfig, $sql);

// Return string
echo 'Successful sent '.$message;

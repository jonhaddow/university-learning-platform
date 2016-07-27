<?php

// include database log in details
$path = $_SERVER['DOCUMENT_ROOT'];
$path .= '/test/dbconfig.php';
include $path;

// Make SQL Request
$sql = 'SELECT * FROM messages ORDER BY id DESC LIMIT 1';
$result = mysqli_query($dbconfig, $sql);
$row = mysqli_fetch_object($result);

// Return row
echo json_encode($row);

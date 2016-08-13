<?php

$server = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'test';

try {
    $db_conn = new PDO("mysql:host=$server;dbname=$dbname", $user, $pass);
    // set the PDO error mode to exception
    $db_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully";
} catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
}

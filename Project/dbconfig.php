<?php

$server = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'test';

$db_conn = new PDO("mysql:host=$server;dbname=$dbname", $user, $pass);
$db_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

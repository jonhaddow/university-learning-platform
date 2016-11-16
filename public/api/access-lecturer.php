<?php

// Get root config file
require_once "access-basic.php";

session_start();

if(!isset($_SESSION["role"])) {
	die("Authentication required");
}

if ($_SESSION["role"] == 0) {
	die("Authentication required");
}
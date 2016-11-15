<?php

// Get root config file
require_once "../config.php";

// Check that session exists
session_start();
if (!isset($_SESSION['username'])) {
	session_destroy();
	header("Location: " . $login_page);
	die();
}

$role = $_SESSION["role"];

if ($role == 0) { // If role is student...

	require_once "studentDash.php";

} else if ($role == 1) { // Else if role is lecturer...

	require_once "lecturerDash.php";

}

?>


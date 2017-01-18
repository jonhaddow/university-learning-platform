<?php

// If user is authenticated then redirect to dashboard
if ($authenticated) {
	header("location: " . DASHBOARD);
	exit();
} else {
	require_once VIEWS . "/loginView.php";
}

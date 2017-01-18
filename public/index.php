<?php

// Get config file
require_once "../resources/config.php";

// Get URL path (cut out the query parameters)
$url = substr(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), 1);
$url = trim($url, "/");
// Split into array
$routes = explode('/', $url);

// Check that user is authenticated
session_start();
$authenticated = isset($_SESSION["username"]);

// Redirect user to correct place dependant on URL path.
if (count($routes) > 0) {

	$goTo = CONTROLLERS . "/" . $routes[0] . "Controller.php";

	if (file_exists($goTo)) {
		require_once $goTo;
		exit();
	}
}

// If nothing matches, take user to launch pages.
if ($authenticated) {
	header("location: " . DASHBOARD);
	exit();
} else {
	header("location: " . LOGIN);
	exit();
}
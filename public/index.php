<?php

// Get config file
require_once "../resources/config.php";

// Get URL path (cut out the query parameters)
$url = substr(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), 1);
$url = trim($url, "/");
// Split into array
$routes = explode('/', $url);

// Check that user is authentication exists
session_start();
$authenticated = isset($_SESSION["username"]);
$redirect = false;

// Redirect user to correct place dependant on URL path.
if (count($routes) == 1) {
	switch ($routes[0]) {
		case "register":
			if (!$authenticated) {
				require_once CONTROLLERS . "/registerController.php";
			} else {
				$redirect = true;
			}
			break;
		case "logoff":
			if ($authenticated) {
				require_once CONTROLLERS . "/logoffController.php";
			} else {
				$redirect = true;
			}
			break;
		case "login":
			if (!$authenticated) {
				require_once CONTROLLERS . "/loginController.php";
			} else {
				$redirect = true;
			}
			break;
		case "dashboard":
			if ($authenticated) {
				require_once CONTROLLERS . "/dashboardController.php";
			} else {
				$redirect = true;
			}
			break;
		default:
			$redirect = true;
			break;
	}
} else {
	$redirect = true;
}

// if user is access api, go to api controller
if ($routes[0] == "api") {
	$redirect = false;
	require_once API . "/apiController.php";

}

// If nothing matches, take user to launch pages.
if ($redirect) {
	if ($authenticated) {
		header("location: " . $dashboard);
	} else {
		header("location: " . $login_page);
	}
}
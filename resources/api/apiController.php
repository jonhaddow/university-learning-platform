<?php

if (isset($_SESSION["role"])) {
	$role = $_SESSION["role"];
} else {
	$role = -1;
}

$url = API . "/" . $routes[1] . "/" . $routes[2];
$no_access = "No access";

switch ($routes[1]) {
	case "login":
		require_once $url;
		break;
	case "register":
		require_once $url;
		break;
	case "feedback":
		if ($role == 0) {
			require_once $url;
		} else {
			echo $no_access;
		}
		break;
	case "modify-map":
		if ($role == 1) {
			require_once $url;
		} else {
			echo $no_access;
		}
		break;
	case "view-map":
		if ($role > -1) {
			require_once $url;
		} else {
			echo $no_access;
		}
		break;
	default:
		echo "Page doesn't exist";
		break;
}


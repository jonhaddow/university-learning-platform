<?php

$role = $_SESSION["role"];

if ($role == 0) { // If role is student...

	require_once VIEWS . "/studentView.php";

} else if ($role == 1) { // Else if role is lecturer...

	if (count($routes) > 1 && $routes[1] == "modify-map") {
		require_once VIEWS . "/lecturerView/lecturerModifyMap.php";
	} else {
		require_once VIEWS . "/lecturerView/lecturerView.php";
	}

}

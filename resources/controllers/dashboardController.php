<?php

if ($authenticated) {
	$role = $_SESSION["role"];
	if ($role == 0) { // If role is student...

		require_once VIEWS . "/dashboard/studentView.php";

	} else if ($role == 1) { // Else if role is lecturer...

		if (count($routes) > 1 && $routes[1] == "modify-map") {
			require_once VIEWS . "/dashboard/lecturerModifyMap.php";
		} else {
			require_once API . "/get-feedback/get-all-students.php";
			require_once VIEWS . "/dashboard/lecturerView.php";
		}
	}
} else {
	header("location: " . LOGIN);
	exit();
}



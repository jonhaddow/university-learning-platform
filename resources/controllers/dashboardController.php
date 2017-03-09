<?php

if ($authenticated) {
	$role = $_SESSION["role"];
	if ($role == 0) { // If role is student...
        require_once API . "/module/get-modules.php";

        if (isset($routes[1])) {
            $module_code = $routes[1];
            require_once VIEWS . "/dashboard/studentView.php";
        } else {
            header("location: " . DASHBOARD . "/" . $modules[0]["Code"]);
        }

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



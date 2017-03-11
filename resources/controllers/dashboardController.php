<?php
if ($authenticated) {
    $role = $_SESSION["role"];
    require_once API . "/module/get-modules.php";
    if ($role == 0) { // If role is student...
        if (isset($routes[2])) {
            $module_code = $routes[2];
            foreach ($modules as $module) {
                if ($module["Code"] == $module_code) {
                    $current_module = $module;
                }
            }
            if (isset($current_module)) {
                require_once VIEWS . "/".$routes[0]."/".$routes[1].".php";
                die();
            }
        }

        header("location: " . STUDENT_VIEW . "/" . $modules[0]["Code"]);
    } else if ($role == 1) { // Else if role is lecturer...
        if (isset($routes[2])) {
            // Get current module
            $module_code = $routes[2];
            foreach ($modules as $module) {
                if ($module["Code"] == $module_code) {
                    $current_module = $module;
                }
            }

            // if module exists
            if (isset($current_module)) {
                require_once API . "/get-feedback/get-all-students.php";
                require_once VIEWS . "/".$routes[0]."/".$routes[1].".php";
                die();
            } else {
                header("location: " . DASHBOARD . "/" . $routes[1] . "/" . $modules[0]["Code"]);
            }
        } else {
            if (isset($routes[1])) {
                header("location: " . DASHBOARD . "/" . $routes[1] . "/" . $modules[0]["Code"]);
            } else {
                header("location: " . LECTURER_VIEW . "/" . $modules[0]["Code"]);
            }
            die();
        }


    }
} else {
    header("location: " . LOGIN);
    exit();
}



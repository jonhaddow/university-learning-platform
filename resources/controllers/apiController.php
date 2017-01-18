<?php

if (isset($_SESSION["role"])) {
	$role = $_SESSION["role"];
} else {
	$role = -1;
}

require_once(API . "/" . $routes[1] . "/" . $routes[2]);
exit();
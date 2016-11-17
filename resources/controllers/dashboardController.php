<?php

$role = $_SESSION["role"];

if ($role == 0) { // If role is student...

	require_once VIEWS . "/studentView.php";

} else if ($role == 1) { // Else if role is lecturer...

	require_once VIEWS . "/lecturerView.php";

}

?>


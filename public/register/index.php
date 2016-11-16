<?php

require_once "../../resources/config.php";

// If in session, redirect to welcome page.
session_start();
if (isset($_SESSION['username'])) {
	header("Location: " . $dashboard);
	die();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<?php
	require_once COMMON_RESOURCES . "/headers.php";
	require_once REGISTER_RESOURCES . "/header.php";
	?>
</head>
<body>
<?php require_once REGISTER_RESOURCES . "/content.php"; ?>
</body>
</html>

<?php

require_once "../../resources/config.php";

session_start();

if (isset($_SESSION['username'])) {
	// Redirect to Welcome page
	header("Location: " . $dashboard);
	die();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<?php
	require_once COMMON_RESOURCES . "/headers.php";
	require_once LOGIN_RESOURCES . "/header.php";
	?>
</head>

<body>
<?php
require_once LOGIN_RESOURCES . "/content.php";
?>
</body>

</html>

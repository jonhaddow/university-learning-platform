<?php

// Get root config file
require_once $_SERVER["DOCUMENT_ROOT"] . "/config.php";

// Check that session exists
session_start();
if (!isset($_SESSION['username'])) {
    session_destroy();
    header("Location: " . $domain_name . "Project/Login");
    die();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Welcome</title>
</head>
<body>
    <a href="<?php echo $domain_name ?>Project/logoff.php">Log Off</a>
    <p>
        Welcome <?php echo $_SESSION['username']; ?>!
    </p>
</body>
</html>

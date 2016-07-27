<?php

session_start();
if (!isset($_SESSION['username'])) {
    session_destroy();
    header('Location: ../Login');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $_SESSION['username']; ?></title>
</head>
<body>
    <a href="logoff.php">Log Off</a>
</body>
</html>

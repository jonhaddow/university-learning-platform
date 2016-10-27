<?php

// Get root config file
require_once $_SERVER["DOCUMENT_ROOT"] . "/config.php";

// Check that session exists
session_start();
if (!isset($_SESSION['username'])) {
    session_destroy();
    header("Location: " . $login_page);
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

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <!-- Roboto Font -->
    <link href="https://fonts.googleapis.com/css?family=Roboto|Roboto+Condensed:300" rel="stylesheet">
    <!-- My style -->
    <link rel="stylesheet" href="style.css">

    <script src="/jsConfig.js"></script>
    <!-- Jquery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>


</head>
<body>
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">Dashboard</a>
            </div>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="<?php echo $logoff ?>">Log Off</a></li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <p>
            Welcome <?php echo $_SESSION['username']; ?>!
        </p>
    </div>
</body>
</html>

<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/config.php";
require_once "manage-post.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <!-- Roboto Font -->
    <link href="https://fonts.googleapis.com/css?family=Roboto|Roboto+Condensed:300" rel="stylesheet">
    <!-- My style -->
    <link rel="stylesheet" href="style.css">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <!-- Jquery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- My Script -->
    <script src="script.js"></script>
</head>

<body>
    <div class="container">
        <div class="col-md-4 col-md-offset-4">
            <form method="post" id="formLogin">
                <h3>Enter Account Details</h3>
                <div id="formGroupUsername" class="form-group">
                    <label for="inputUsername">Username</label>
                    <input type="text" name="user" class="form-control" id="inputUsername" placeholder="Username">
                </div>
                <div id="formGroupPassword" class="form-group">
                    <label for="inputPassword">Password</label>
                    <input type="password" name="pass" class="form-control" id="inputPassword" placeholder="Password">
                </div>
                <?php if (isset($invalid_input)) {
                    echo '
                    <div class="error">Login details are incorrect</div>
                    ';
                } ?>
                <button class="btn btn-default" type="submit" id="btnLogin">Sign in</button>
                or <a id="register" href="<?php echo $domain_name ?>Project/Register">Register</a>
            </form>

        </div>
    </div>
</body>

</html>

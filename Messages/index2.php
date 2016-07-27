<?php
session_start();
?>

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Messages</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="script.js"></script>
</head>

<body>
    <div id="response"></div>
    <button id="getMessageButton">Get Latest Message</button>
    <br>
    <br>
    <input type="text" id="messageInput">
    <br>
    <button id="sendMessageButton">Send Message</button>
    <br>
    <div id="postResponse"></div>
    <p>
        <?php
        // Echo session variables that were set on previous page
        echo 'Favorite color is '.$_SESSION['favcolor'].'.<br>';
        echo 'Favorite animal is '.$_SESSION['favanimal'].'.';

        print_r($_SESSION);
        ?>
    </p>
</body>

</html>

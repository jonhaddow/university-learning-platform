<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/config.php";

session_start();
if (isset($_SESSION['username'])) {
    // Redirect to Welcome page
    header("Location: " . $domain_name . "Project");
    die();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $url = $domain_name . "Project/API/Login.php";
    $post_fields = array('user' => $_POST["user"], 'pass' => $_POST["pass"]);

    // Send post data to login API via Curl
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    // Decode response
    $jObject = json_decode($response,true);
    $isSuccessful = $jObject["Successful"];
    if ( $isSuccessful ) {
        // If login Successful, set Session username and go to welcome page
        $_SESSION["username"] = $_POST["user"];
        header("Location: " . $domain_name . "Project");
        die();
    } else {
        $invalidLogin = true;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="script.js"></script>
</head>

<body>
    <form id="loginform" method="post">
        <table>
            <tr>
                <td>Username</td>
                <td>
                    <input type="text" name="user">
                </td>
            </tr>
            <tr>
                <td>Password</td>
                <td>
                    <input type="text" name="pass">
                </td>
            </tr>
        </table>
        <?php
            if (isset($invalidLogin)) {
                echo "<div style='color:red;'>Invalid Login</div>";
            }
         ?>
        <button type="submit">Login</button>
    </form>
    <br>
    <button id="register">Register</button>
</body>

</html>

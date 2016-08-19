<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/config.php";

// If in session, redirect to welcome page.
session_start();
if (isset($_SESSION['username'])) {
    header("Location: " . $domain_name . "Project");
    die();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $url = $domain_name . "Project/API/Register.php";
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

    // Get response values
    $is_successful = $jObject["Successful"];
    $username_available = $jObject["UsernameAvailable"];
    $password_valid = $jObject["PasswordValid"];

    if ($is_successful && $username_available && $password_valid) {
        // If login Successful, go to login
        header("Location: " . $domain_name . "Project/Login");
        die();
    }
}
?>

<html>
    <head>
        <meta charset="utf-8">
        <title>Registration</title>
    </head>
    <body>
        <form method="post">
            <label>Enter account details</label>
            <table>
                <tr>
                    <td>Username</td>
                    <td>
                        <input type="text" name="user" required>
                    </td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td>
                        <input type="text" name="pass" required>
                    </td>
                </tr>
            </table>
            <?php
                if (isset($username_available) && !$username_available) {
                    echo "<div style='color:red;'>Username Taken!</div>";
                } else if (isset($password_valid) && !$password_valid) {
                    echo "<div style='color:red;'>Password needs to be at least 6 characters</div>";
                } else if (isset($is_successful) && !$is_successful) {
                    echo "<div style='color:red;'>Connection error!</div>";
                }
             ?>
            <button type="submit" name="button">Register</button>
        </form>
    </body>
</html>

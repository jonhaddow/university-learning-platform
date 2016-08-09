<?php

// If in session, redirect to welcome page.
session_start();
if (isset($_SESSION['username'])) {
    header('Location: welcome.php');
}

// include database log in details
$path = $_SERVER['DOCUMENT_ROOT'];
$path .= '/dbconfig.php';
include $path;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // username and password received from loginform
    $username = mysqli_real_escape_string($dbconfig, $_POST['user']);
    $password = mysqli_real_escape_string($dbconfig, $_POST['pass']);

    // Initialise prepared statement
    $stmt = mysqli_stmt_init($dbconfig);
    // Write statement
    mysqli_stmt_prepare($stmt, 'SELECT * FROM users WHERE Username = ?');
    // Add POST parameters
    mysqli_stmt_bind_param($stmt, 's', $username);
    // Excute statement
    mysqli_stmt_execute($stmt);
    // Get result
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_fetch_array($result)) {

        // If username already exists, notify user
        $invalidLogin = true;

    } else {

    // SQL STATEMENT TO ADD USER TO DATABASE.

    }
}

?>

<html>
    <head>
        <meta charset="utf-8">
        <title>Registration</title>
    </head>
    <body>
        <form action="#" method="post">
            <label>Enter account details</label>
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
                    echo "<div style='color:red;'>Username Taken!</div>";
                }
             ?>
            <button type="submit" name="button">Register</button>
        </form>
    </body>
</html>

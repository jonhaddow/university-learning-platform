<?php

session_start();
include 'dbconfig.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // username and password received from loginform
    $username = mysqli_real_escape_string($dbconfig, $_POST['user']);
    $password = mysqli_real_escape_string($dbconfig, $_POST['pass']);

    // Initialise prepared statement
    $stmt = mysqli_stmt_init($dbconfig);
    // Write statement
    mysqli_stmt_prepare($stmt, 'SELECT * FROM user WHERE username = ? AND password = ?');
    // Add POST parameters
    mysqli_stmt_bind_param($stmt, 'ss', $username, $password);
    // Excute statement
    mysqli_stmt_execute($stmt);
    // Get result
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_fetch_array($result)) {

        // If successful, attach username to session
        $_SESSION['username'] = $username;
        header('Location: /Login/welcome.php');
    } else {
        $invalidLogin = true;
    }

    // Close connection
    mysqli_stmt_close($stmt);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
</head>

<body>
    <form action="#" method="post">
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
</body>

</html>

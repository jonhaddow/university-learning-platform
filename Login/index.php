<?php

// Start session
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

    if ($row = mysqli_fetch_assoc($result)) {

        // Get hashed password from DB
        $hashed_password = $row["HashedPassword"];

        // Verify user-entered password
        if (password_verify($password, $hashed_password)) {

            // If successful, attach username to session
            $_SESSION['username'] = $username;
            header('Location: welcome.php');

        } else {
            $invalidLogin = true;
        }

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

    <script   src="https://code.jquery.com/jquery-3.1.0.min.js"   integrity="sha256-cCueBR6CsyA4/9szpPfrX3s49M9vUU5BgtiJj06wt/s="   crossorigin="anonymous"></script>
    <script src="script.js"></script>
</head>

<body>
    <form method="post">
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

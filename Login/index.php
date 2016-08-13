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
    $username = $_POST["user"];
    $password = $_POST["pass"];

    // Make query to find a matching username
    $stmt = $db_conn->prepare('SELECT * FROM users WHERE Username = :username');
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    // Get result
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {

        // Verify user-entered password
        if (password_verify($password, $row["HashedPassword"])) {

            // If successful, attach username to session
            $_SESSION['username'] = $username;
            header('Location: welcome.php');

        } else {
            $invalidLogin = true;
        }

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

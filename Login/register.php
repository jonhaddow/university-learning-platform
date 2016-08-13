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

    // Get username and password
    $username = $_POST["user"];
    $password = $_POST["pass"];

    // Make query to find if username exists
    $stmt = $db_conn->prepare('SELECT * FROM users WHERE Username = :username');
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    // Get result
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {

        // If username already exists, notify user
        $invalidUser = true;

    } else if (strlen($password) < 6) {

        // Validate password length
        $invalidInput = true;

    } else {

        // Enter new user into database
        $stmt = $db_conn->prepare('INSERT INTO users(Username, HashedPassword) VALUES (:username, :password)');
        $stmt->bindParam(':username', $username);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bindParam(':password', $hashed_password);
        if ($stmt->execute()){
            // Go to login page
            header("Location: index.php");
        }
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
                if (isset($invalidUser)) {
                    echo "<div style='color:red;'>Username Taken!</div>";
                } else if (isset($invalidInput)) {
                    echo "<div style='color:red;'>Username and password need to be at least 6 characters</div>";
                }
             ?>
            <button type="submit" name="button">Register</button>
        </form>
    </body>
</html>

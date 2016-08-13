<?php

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
            echo "Successful Login";

        } else {
            $invalidLogin = true;
        }

    } else {
        $invalidLogin = true;
    }
    if (isset($invalidLogin)) {
        echo "Unsuccessful Login";
    }
}

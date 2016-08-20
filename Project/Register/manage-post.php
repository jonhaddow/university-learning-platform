<?php

// If in session, redirect to welcome page.
session_start();
if (isset($_SESSION['username'])) {
    header("Location: " . $domain_name . "Project");
    die();
}

// Get response values
$is_successful = true;
$username_available = true;
$password_valid = true;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // include database log in details
    require_once $_SERVER["DOCUMENT_ROOT"] . "/dbconfig.php";

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

        // Username already exists
        $username_available = false;

    } else if (strlen($password) < 6) {

        // Validate password length
        $password_valid = false;

    } else {

        // Enter new user into database
        $stmt = $db_conn->prepare('INSERT INTO users(Username, HashedPassword) VALUES (:username, :password)');
        $stmt->bindParam(':username', $username);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bindParam(':password', $hashed_password);
        if (!($stmt->execute())) {
            $is_successful = false;
        }
    }

    if ($is_successful && $username_available && $password_valid) {
        // If login Successful, go to login
        header("Location: " . $domain_name . "Project/Login");
        die();
    }
}

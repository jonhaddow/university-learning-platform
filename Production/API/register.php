<?php

// include database log in details
require_once "../dbconfig.php";

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
        $json_response["status"] = "fail";
        $json_response["data"] = "Username unavailable";

    } else if (strlen($password) < 6) {

        // Validate password length
        $json_response["status"] = "fail";
        $json_response["data"] = "Password should be at least 6 characters";

    } else {

        // Enter new user into database
        $stmt = $db_conn->prepare('INSERT INTO users(Username, HashedPassword) VALUES (:username, :password)');
        $stmt->bindParam(':username', $username);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bindParam(':password', $hashed_password);
        if ($stmt->execute()) {
            $json_response["status"] = "success";
        } else {
            $json_response["status"] = "error";
            $json_response["message"] = "Cannot connect to database";
        }
    }

    echo json_encode($json_response);
}

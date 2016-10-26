<?php

session_start();

// include database log in details
require_once $_SERVER["DOCUMENT_ROOT"] . "/dbconfig.php";

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
        if (!password_verify($password, $row["HashedPassword"])) {
            $json_response["status"] = "success";
            $_SESSION["username"] = $username;
        }

    } else {
        $json_response["status"] = "fail";
    }
    echo json_encode($json_response);
}

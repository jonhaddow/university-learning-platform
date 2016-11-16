<?php

require_once "../permissions/access-basic.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // username and password received from loginform
    $username = $_POST["user"];
    $password = $_POST["pass"];

    // Make query to find a matching username
    $stmt = $db_conn->prepare('SELECT * FROM users WHERE Username = :username');
    $stmt->bindParam(':username', $username);
    if (!$stmt->execute()) {
        $json_response["status"] = "error";
        $json_response["message"] = "Cannot connect to database";
    }

    // Get result
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Username exists
    if ($row) {

        // Verify user-entered password
        if (password_verify($password, $row["HashedPassword"])) {
            $json_response["status"] = "success";

	        session_start();

	        // set session properties
	        $_SESSION["userId"] = $row["UserId"];
            $_SESSION["username"] = $username;
	        $_SESSION["role"] = $row["Role"];
        } else {
            $json_response["status"] = "fail";
            $json_response["data"] = "Incorrect password";
        }

    } else {
        $json_response["status"] = "fail";
        $json_response["data"] = "Username does not exists";
    }
    echo json_encode($json_response);
}

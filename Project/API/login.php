<?php

// include database log in details
require_once $_SERVER["DOCUMENT_ROOT"] . "/dbconfig.php";

$json_response = array(
    "Successful" => TRUE
);

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
            $json_response["Successful"] = FALSE;
        }

    } else {
        $json_response["Successful"] = FALSE;
    }
    echo json_encode($json_response);
}

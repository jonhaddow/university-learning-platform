<?php
session_start();

if (isset($_SESSION['username'])) {
    // Redirect to Welcome page
    header("Location: " . $domain_name);
    die();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // include database log in details
    require_once $_SERVER["DOCUMENT_ROOT"] . "/dbconfig.php";

    // username and password received from loginform
    $username = $_POST["user"];
    $password = $_POST["pass"];

    // Make query to find a matching username
    $stmt = $db_conn->prepare('SELECT * FROM users WHERE Username = :username');
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    // Get result
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && password_verify($password, $row["HashedPassword"])) {
        // If login Successful, set Session username and go to welcome page
        $_SESSION["username"] = $_POST["user"];
        header("Location: " . $domain_name);
        die();
    } else {
        $invalid_input = true;
    }
}

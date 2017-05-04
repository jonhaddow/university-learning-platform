<?php

// Get username and password received from loginform
$username = $_POST["user"];
$password = $_POST["pass"];


// Check that username and password are not null
if (!$username || !$password) {
	$json_response["status"] = "fail";
	$json_response["data"] = "Incorrect input";
	exit(json_encode($json_response));
}

// Look for matching username
$stmt = $db_conn->prepare('SELECT * FROM users WHERE Username = :username');
$stmt->bindParam(':username', $username);

if (!$stmt->execute()) {
	$json_response["status"] = "error";
	$json_response["message"] = "Cannot connect to database";
	exit(json_encode($json_response));
}

// Get result
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if username exists or if password is incorrect
if (!$row || !password_verify($password, $row["HashedPassword"])) {
	$json_response["status"] = "fail";
	$json_response["data"] = "Login details are incorrect";
	exit(json_encode($json_response));
}

$json_response["status"] = "success";

// set session properties
$_SESSION["userId"] = $row["UserId"];
$_SESSION["username"] = $username;
$_SESSION["role"] = $row["Role"];
exit(json_encode($json_response));
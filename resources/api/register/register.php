<?php

// Get post data
$username = $_POST["user"];
$password = $_POST["pass"];
$role = $_POST["role"];

// Make query to find if username exists
$stmt = $db_conn->prepare('SELECT * FROM users WHERE Username = :username');
$stmt->bindParam(':username', $username);
if (!$stmt->execute()) {
	$json_response["status"] = "error";
	$json_response["message"] = "Cannot connect to database";
	exit(json_encode($json_response));
}

// Get result
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// If username already exists, notify user
if ($row) {
	$json_response["status"] = "fail";
	$json_response["data"] = "Username unavailable";
	exit(json_encode($json_response));
}

// Validate password length
if (strlen($password) < 6) {
	$json_response["status"] = "fail";
	$json_response["data"] = "Password should be at least 6 characters";
	exit(json_encode($json_response));
}

// Enter new user into database
$stmt = $db_conn->prepare('
	INSERT INTO users(Username, HashedPassword, Role) 
	VALUES (:username, :password, :role)'
);
$stmt->bindParam(':username', $username);
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$stmt->bindParam(':password', $hashed_password);
$stmt->bindParam(':role', $role);
if ($stmt->execute()) {
	$json_response["status"] = "success";
} else {
	$json_response["status"] = "error";
	$json_response["message"] = "Cannot connect to database";
}
exit(json_encode($json_response));


<?php

// Get root config file
require_once "../config.php";

// Check that session exists
session_start();
if (!isset($_SESSION['username'])) {
	session_destroy();
	header("Location: " . $login_page);
	die();
}

// include database log in details
require_once "../dbconfig.php";

// Send SQL query to find all dependencies
$sql = "
    SELECT ParentId, ChildId
    FROM dependencies
";

// Execute statement
$stmt = $db_conn->prepare($sql);
if ($stmt->execute()) {
    $json_response["status"] = "success";
} else {
    $json_response["status"] = "error";
    $json_response["message"] = "Unable to communicate with the database";
}

// Fetch all of the values of the parent and child dependencies
$dependencies = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($dependencies) == 0) {
    $json_response["data"] = "No dependencies exist";
} else {
    foreach ($dependencies as $dependency) {
        $json_response["data"][] = [
            "ParentId" => $dependency["ParentId"],
            "ChildId" => $dependency["ChildId"]
        ];
    }
}

// Output Json
echo json_encode($json_response);
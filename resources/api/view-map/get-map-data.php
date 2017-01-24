<?php

if (!($_SESSION["role"] >= 0)) {exit();}

// Get all topics
$sql = "SELECT * FROM topics";

// Execute statement
$stmt = $db_conn->prepare($sql);
if (!$stmt->execute()) {
	$json_response["status"] = "error";
	exit(json_encode($json_response));
}

// Fetch all of rows
$topics = $stmt->fetchAll(PDO::FETCH_ASSOC);
$json_response["topics"] = $topics;

// Get all dependencies
$sql = "SELECT ParentId, ChildId FROM dependencies";

// Execute statement
$stmt = $db_conn->prepare($sql);
if (!$stmt->execute()) {
	$json_response["status"] = "error";
	exit(json_encode($json_response));
}

// Fetch all of the rows
$dependencies = $stmt->fetchAll(PDO::FETCH_ASSOC);
$json_response["dependencies"] = $dependencies;

// Output Json
exit(json_encode($json_response));
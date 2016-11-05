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

// get topic name
$topicName = $_GET["topic"];

// Check that topic exists
$sql = "
    SELECT TopicId
    FROM topics
    WHERE  Name = :topic
";
$stmt = $db_conn->prepare($sql);
$stmt->bindParam(":topic", $topicName);
$stmt->execute();
$response = $stmt->fetchAll(PDO::FETCH_ASSOC);
$exists = $stmt->rowCount();
if ($exists == 0) {
    $json_response["status"] = "fail";
    $json_response["data"] = "The topic given does not exist";
    echo json_encode($json_response);
    die();
}

// find any parent dependencies
$sql = "
    SELECT ParentId
    FROM dependencies
    WHERE ChildId = (
        SELECT TopicId
        FROM topics
        WHERE Name = :topicName
    )
";
$stmt = $db_conn->prepare($sql);
$stmt->bindParam(":topicName", $topicName);
$stmt->execute();
$response = $stmt->fetchAll(PDO::FETCH_ASSOC);
$parents = array();
foreach ($response as $parent) {
    $parents[] = $parent["ParentId"];
}

// Delete all parent dependencies
$sql = "
    DELETE
    FROM dependencies
    WHERE ChildId = (
        SELECT TopicId
        FROM topics
        WHERE Name = :topic
    )
";
$stmt = $db_conn->prepare($sql);
$stmt->bindParam(":topic", $topicName);
$stmt->execute();

// find any child dependencies
$sql = "
    SELECT ChildId
    FROM dependencies
    WHERE ParentId = (
        SELECT TopicId
        FROM topics
        WHERE Name = :topicName
    )
";
$stmt = $db_conn->prepare($sql);
$stmt->bindParam(":topicName", $topicName);
$stmt->execute();
$response = $stmt->fetchAll(PDO::FETCH_ASSOC);
$children = array();
foreach ($response as $child) {
    $children[] = $child["ChildId"];
}

// Delete all child dependencies
$sql = "
    DELETE
    FROM dependencies
    WHERE ParentId = (
        SELECT TopicId
        FROM topics
        WHERE Name = :topic
    )
";
$stmt = $db_conn->prepare($sql);
$stmt->bindParam(":topic", $topicName);
$stmt->execute();

// Create a new dependency between all parents and all children
foreach ($parents as $parent) {
    foreach ($children as $child) {
        $sql = "
            INSERT INTO dependencies(ParentId, ChildId)
            VALUES (:parentId, :childId)
        ";
        $stmt = $db_conn->prepare($sql);
        $stmt->bindParam(":parentId", $parent);
        $stmt->bindParam(":childId", $child);
        $stmt->execute();
    }
}

// Delete topic
$sql = "
    DELETE FROM topics
    WHERE Name = :topicName
";
$stmt = $db_conn->prepare($sql);
$stmt->bindParam(":topicName", $topicName);

if ($stmt->execute()) {
    $json_response["status"] = "success";
    $json_response["data"] = "null";
} else {
    $json_response["status"] = "error";
    $json_response["message"] = "Unable to communicate with the database";
}

echo json_encode($json_response);

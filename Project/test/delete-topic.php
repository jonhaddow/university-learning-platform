<?php

// include database log in details
require_once $_SERVER["DOCUMENT_ROOT"] . "/dbconfig.php";

// get topic name
$topicName = $_GET["topic"];

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
$stmt->execute();

die();

$stmt = $db_conn->prepare($sql);
$stmt->bindParam(":parentId", $dependencyIds[0]);
$stmt->bindParam(":childId", $dependencyIds[1]);
if ($stmt->execute()) {
    $json_response["status"] = "success";
    $json_response["data"] = "null";
} else {
    $json_response["status"] = "error";
    $json_response["message"] = "Unable to communicate with the database";
}

// Output Json
echo json_encode($json_response);

<?php

// include database log in details
require_once $_SERVER["DOCUMENT_ROOT"] . "/dbconfig.php";

// get connected node names
$fromNode = $_GET["from"];
$toNode = $_GET["to"];

// find any parent dependencies
$sql = "
    DELETE
    FROM dependencies
    WHERE ParentId = (
        SELECT TopicId
        FROM topics
        WHERE Name = :parent
    )
    AND ChildId = (
        SELECT TopicId
        FROM topics
        WHERE Name = :child
    )
";
$stmt = $db_conn->prepare($sql);
$stmt->bindParam(":parent", $fromNode);
$stmt->bindParam(":child", $toNode);

if ($stmt->execute()) {
    $json_response["status"] = "success";
    $json_response["data"] = "null";
} else {
    $json_response["status"] = "error";
    $json_response["message"] = "Unable to communicate with the database";
}

echo json_encode($json_response);

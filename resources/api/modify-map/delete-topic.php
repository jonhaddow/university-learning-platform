<?php

if ($_SESSION["role"] != 1) {
    exit();
}

// get topic name
$topicName = $_POST["topic"];

// Check that topic exists
$sql = "
    SELECT TopicId
    FROM topics
    WHERE Name = :topic
";
$stmt = $db_conn->prepare($sql);
$stmt->bindParam(":topic", $topicName);
$stmt->execute();
$response = $stmt->fetchAll(PDO::FETCH_ASSOC);
$exists = $stmt->rowCount();
if ($exists == 0) {
    $json_response["status"] = "fail";
    $json_response["data"] = "The topic given does not exist";
    exit(json_encode($json_response));
}
$topicId = $response[0]["TopicId"];

// find any parent dependencies
$sql = "
    SELECT ParentId
    FROM dependencies
    WHERE ChildId = :topicId
";
$stmt = $db_conn->prepare($sql);
$stmt->bindParam(":topicId", $topicId);
$stmt->execute();
$response = $stmt->fetchAll(PDO::FETCH_ASSOC);
$parents = array();
foreach ($response as $parent) {
    $parents[] = $parent["ParentId"];
}

// Delete all parent dependencies
$sql = "
    DELETE FROM dependencies
    WHERE ChildId = :topicId
";
$stmt = $db_conn->prepare($sql);
$stmt->bindParam(":topicId", $topicId);
$stmt->execute();

// find any child dependencies
$sql = "
    SELECT ChildId
    FROM dependencies
    WHERE ParentId = :topicId
";
$stmt = $db_conn->prepare($sql);
$stmt->bindParam(":topicId", $topicId);
$stmt->execute();
$response = $stmt->fetchAll(PDO::FETCH_ASSOC);
$children = array();
foreach ($response as $child) {
    $children[] = $child["ChildId"];
}

// Delete all child dependencies
$sql = "
    DELETE FROM dependencies
    WHERE ParentId = :topicId
";
$stmt = $db_conn->prepare($sql);
$stmt->bindParam(":topicId", $topicId);
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

// Delete all feedback related to topic
$sql = "
	DELETE FROM feedback
	WHERE TopicId = :topicId
";
$stmt = $db_conn->prepare($sql);
$stmt->bindParam(":topicId", $topicId);
$stmt->execute();

// Delete topic
$sql = "
    DELETE FROM topics
    WHERE TopicId = :topicId
";
$stmt = $db_conn->prepare($sql);
$stmt->bindParam(":topicId", $topicId);
$stmt->execute();
$json_response["status"] = "success";
$json_response["data"] = "null";

exit(json_encode($json_response));
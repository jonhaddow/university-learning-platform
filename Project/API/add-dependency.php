<?php

// include database log in details
require_once $_SERVER["DOCUMENT_ROOT"] . "/dbconfig.php";

// get parent and child names
$dependency_names = array($_POST["parent"], $_POST["child"]);
$dependency_ids = array();

// find ids for both child and parent name given
for ($i=0; $i < 2; $i++) {

    // Send SQL query to get id
    $sql = "
        SELECT TopicId
        FROM topics
        WHERE Name = :name
    ";

    // Execute statement
    $stmt = $db_conn->prepare($sql);
    $stmt->bindParam(":name", $dependency_names[$i]);
    $stmt->execute();

    // Fetch id
    $response = $stmt->fetch(PDO::FETCH_ASSOC);
    $dependency_ids[$i] = $response["TopicId"];
}

// If dependency names given don't exist
if ($dependency_ids[0] == null || $dependency_ids[1] == null) {
    $json_response["status"] = "fail";
    $json_response["data"] = "One or more dependencies given do not exist.";
    echo json_encode($json_response);
    die();
}

$sql = "
    INSERT INTO dependencies(ParentId, ChildId)
    VALUES (:parentId, :childId)
";

$stmt = $db_conn->prepare($sql);
$stmt->bindParam(":parentId", $dependency_ids[0]);
$stmt->bindParam(":childId", $dependency_ids[1]);
if ($stmt->execute()) {
    $json_response["status"] = "success";
    $json_response["data"] = "null";
} else {
    $json_response["status"] = "error";
    $json_response["message"] = "Unable to communicate with the database";
}

// Output Json
echo json_encode($json_response);

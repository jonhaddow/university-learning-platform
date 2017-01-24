<?php

if ($_SESSION["role"] != 1) {exit();}

// get parent and child names
$dependency_names = array($_POST["parent"], $_POST["child"]);
$dependency_ids = array();

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
    $dependency_ids[] = $response["TopicId"];
}

// If dependency names given don't exist
if ($dependency_ids[0] == null || $dependency_ids[1] == null) {
    $json_response["status"] = "fail";
    $json_response["data"] = "One or more dependencies given do not exist.";
    echo json_encode($json_response);
    die();
}

// find any parent dependencies
$sql = "
    DELETE
    FROM dependencies
    WHERE ParentId = :parent
    AND ChildId = :child
";

$stmt = $db_conn->prepare($sql);
$stmt->bindParam(":parent", $dependency_ids[0]);
$stmt->bindParam(":child", $dependency_ids[1]);

if ($stmt->execute()) {
    $json_response["status"] = "success";
    $json_response["data"] = "null";
} else {
    $json_response["status"] = "error";
    $json_response["message"] = "Unable to communicate with the database";
}

echo json_encode($json_response);

<?php

// include database log in details
require_once $_SERVER["DOCUMENT_ROOT"] . "/dbconfig.php";

// get parent and child names
$dependencyNames = array($_POST["parent"], $_POST["child"]);

for ($i=0; $i < 2; $i++) {

    // Send SQL query to get id
    $sql = "
        SELECT TopicId
        FROM topics
        WHERE Name = :name
    ";

    // Execute statement
    $stmt = $db_conn->prepare($sql);
    $stmt->bindParam(":name", $dependencyNames[$i]);
    $stmt->execute();

    // Fetch id
    $response = $stmt->fetch(PDO::FETCH_ASSOC);
    $dependencyIds[] = $response["TopicId"];
}

$sql = "
    INSERT INTO dependencies(ParentId, ChildId)
    VALUES (:parentId, :childId)
";

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

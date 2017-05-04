<?php

if ($_SESSION["role"] != 1) {exit();}

// get parent and child ids
$dependency_ids = array($_POST["parent"], $_POST["child"]);

// remove the dependency
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

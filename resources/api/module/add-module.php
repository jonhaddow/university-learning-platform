<?php

if ($_SESSION["role"] != 1) {
    exit();
}

// Get module code and name as parameters
$code = $_POST["moduleCode"];
$name = $_POST["moduleName"];
$validInput = true;

// Validate code and name data
if (strlen($code) == 0 || strlen($name) == 0) {
    $result["status"] = "error";
    $result["message"] = "length";
    die(json_encode($result));
}

// Add module to table.
$stmt = $db_conn->prepare("INSERT INTO modules(Code, Name) VALUES (:code, :name)");
$stmt->bindParam("code", $_POST["moduleCode"]);
$stmt->bindParam("name", $_POST["moduleName"]);
try {
    $stmt->execute();
} catch (Exception $exception) {
    $result["status"] = "error";
    $result["message"] = "duplicate";
    die(json_encode($result));
}

$result["status"] = "success";
echo json_encode($result);
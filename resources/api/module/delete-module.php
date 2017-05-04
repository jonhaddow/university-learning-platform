<?php

if ($_SESSION["role"] != 1) {
    exit();
}

// Get module code to delete
$code = $_POST["moduleCode"];

// Delete module from modules table.
$stmt = $db_conn->prepare("DELETE FROM modules WHERE Code = :code");
$stmt->bindParam(":code", $code);
$stmt->execute();

// delete topics within module
$stmt = $db_conn->prepare("DELETE FROM topics WHERE ModuleCode = :code");
$stmt->bindParam(":code", $code);
$stmt->execute();

$result["status"] = "success";
echo json_encode($result);
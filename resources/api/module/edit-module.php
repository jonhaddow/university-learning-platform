<?php

if ($_SESSION["role"] != 1) {
    exit();
}

// Get parameters sent.
$code = $_POST["moduleCode"];
$name = $_POST["moduleName"];
$oldCode = $_POST["oldModuleCode"];

// Validate code and name.
if (strlen($code) == 0 || strlen($name) == 0) {
    $result["status"] = "error";
    $result["message"] = "length";
    die(json_encode($result));
}

// Update old row with new data.
$stmt = $db_conn->prepare("UPDATE modules SET Code = :code, Name = :name WHERE Code = :oldCode");
$stmt->bindParam(":code", $code);
$stmt->bindParam(":name", $name);
$stmt->bindParam(":oldCode", $oldCode);
try {
    $stmt->execute();
} catch (Exception $exception) {

    // Catch exception if a duplicate exists in table.
    $result["status"] = "error";
    $result["message"] = "duplicate";
    $result["exception"] = $exception;
    die(json_encode($result));
}

// update module code of related topics
if ($code != $oldCode) {
    $stmt = $db_conn->prepare("UPDATE topics SET ModuleCode = :code WHERE ModuleCode = :oldCode");
    $stmt->bindParam(":code", $code);
    $stmt->bindParam(":oldCode", $oldCode);
    $stmt->execute();
}

$result["status"] = "success";
echo json_encode($result);
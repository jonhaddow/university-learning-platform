<?php

if ($_SESSION["role"] != 1) {
    exit();
}

$code = $_POST["moduleCode"];
$name = $_POST["moduleName"];
$oldCode = $_POST["oldModuleCode"];

if (strlen($code) == 0 || strlen($name) == 0) {
    $result["status"] = "error";
    $result["message"] = "length";
    die(json_encode($result));
}



$stmt = $db_conn->prepare("UPDATE modules SET Code = :code, Name = :name WHERE Code = :oldCode");
$stmt->bindParam(":code", $code);
$stmt->bindParam(":name", $name);
$stmt->bindParam(":oldCode", $oldCode);
try {
    $stmt->execute();
} catch (Exception $exception) {
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
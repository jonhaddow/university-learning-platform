<?php

if ($_SESSION["role"] != 0) {exit();}

$userId = $_SESSION["userId"];
$topicId = $_POST["topicId"];

// Delete mark from feedback table.
$sql = "
	DELETE FROM feedback
	WHERE TopicId = :topicid
	AND UserId = :userid
";

$stmt = $db_conn->prepare($sql);
$stmt->bindParam(":topicid", $topicId);
$stmt->bindParam(":userid", $userId);
if ($stmt->execute()) {
	$json_response["status"] = "success";
	$json_response["data"] = "null";
} else {
	$json_response["status"] = "error";
	$json_response["message"] = "Unable to communicate with the database";
}

echo json_encode($json_response);
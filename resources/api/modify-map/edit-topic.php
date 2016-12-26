<?php

// get name of topic
$topic_name = $_POST["topicName"];
$topic_id = $_POST["topicId"];

// check the length of topic_name
if (strlen($topic_name) > 30) {
	$json_response["status"] = "fail";
	$json_response["data"]["length"] = "Topic name is too long.";
	echo json_encode($json_response);
	die();
}

// Send SQL query to update topic
$sql = "
UPDATE topics SET Name = :topicName WHERE TopicId = :topicId
";

// Execute statement
$stmt = $db_conn->prepare($sql);
$stmt->bindParam(":topicName", $topic_name);
$stmt->bindParam(":topicId", $topic_id);
try {
	if ($stmt->execute()) {
		$json_response["status"] = "success";
	} else {
		$json_response["status"] = "error";
	}
} catch (Exception $e) {
	// if duplicate detected, handle response
	$json_response["status"] = "fail";
	$json_response["data"]["duplicate"] = "Topic name '" . $topic_name . "' already exists.";
}

// Output Json
echo json_encode($json_response);

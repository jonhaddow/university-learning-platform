<?php

if ($_SESSION["role"] != 1) {
	exit();
}

$students = $_GET["studentId"];

// If list of students are submitted, split it up.
if ($students) {
	$sIds = implode(',', $students);

	// Get all feedback from the list of students about this particular topic.
	$sql = $db_conn->prepare("
		SELECT Mark
		FROM feedback
		WHERE TopicId = :topicid AND UserId IN ($sIds)
	");
	$sql->bindParam(":topicid", $_GET["topicId"]);
} else {
    // Get all feedback about this particular topic.
	$sql = $db_conn->prepare("
		SELECT Mark
		FROM feedback
		WHERE TopicId = :topicid
	");
	$sql->bindParam(":topicid", $_GET["topicId"]);
}

if ($sql->execute()) {
	$results = $sql->fetchAll(PDO::FETCH_ASSOC);
	echo json_encode($results);
} else {
	$json_response["status"] = "fail";
	echo json_encode($json_response);
}
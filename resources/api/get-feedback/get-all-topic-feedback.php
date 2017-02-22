<?php

if ($_SESSION["role"] != 1) {
	exit();
}

$students = $_GET["studentId"];

if ($students) {
	$sIds = implode(',', $students);
	$sql = $db_conn->prepare("
		SELECT Mark
		FROM feedback
		WHERE TopicId = :topicid AND UserId IN ($sIds)
	");
	$sql->bindParam(":topicid", $_GET["topicId"]);
} else {
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
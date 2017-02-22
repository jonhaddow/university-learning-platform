<?php

if ($_SESSION["role"] != 1) {
	exit();
}

$students = $_GET["studentId"];

$sIds = implode(',', $students);
$sql = $db_conn->prepare("
	SELECT TopicId, AVG(Mark) AS Mark
	FROM (SELECT TopicId, Mark FROM feedback WHERE UserId IN ($sIds)) AS T1
	GROUP BY TopicId
");

if ($sql->execute()) {
	$results = $sql->fetchAll(PDO::FETCH_ASSOC);
	echo json_encode($results);
} else {
	$json_response["success"] = "fail";
	echo json_encode($json_response);
}
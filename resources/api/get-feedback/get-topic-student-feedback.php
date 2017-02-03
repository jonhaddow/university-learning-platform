<?php

if ($_SESSION["role"] != 1) {
	exit();
}

$sql = $db_conn->prepare("
	SELECT TopicId, Mark
	FROM feedback
	WHERE UserId = :studentid
");
$sql->bindParam(":studentid", $_GET["studentId"]);

if ($sql->execute()) {
	$results = $sql->fetchAll(PDO::FETCH_ASSOC);
	echo json_encode($results);
} else {
	$json_response["success"] = "fail";
	echo json_encode($json_response);
}
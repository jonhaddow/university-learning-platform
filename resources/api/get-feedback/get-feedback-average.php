<?php

//if ($_SESSION["role"] != 1) {
//	exit();
//}

$sql = $db_conn->prepare("
	SELECT UserId, TopicId, Mark
	FROM feedback
");

if ($sql->execute()) {
	$results = $sql->fetchAll(PDO::FETCH_ASSOC);
	echo json_encode($results);
} else {
	$json_response["success"] = "fail";
	echo json_encode($json_response);
}
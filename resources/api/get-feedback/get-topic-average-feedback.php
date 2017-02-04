<?php

//if ($_SESSION["role"] != 1) {
//	exit();
//}

$sql = $db_conn->prepare("
	SELECT TopicId, AVG(Mark) AS Mark 
	FROM `feedback` 
	GROUP BY TopicId
");

if ($sql->execute()) {
	$results = $sql->fetchAll(PDO::FETCH_ASSOC);
	echo json_encode($results);
} else {
	$json_response["success"] = "fail";
	echo json_encode($json_response);
}
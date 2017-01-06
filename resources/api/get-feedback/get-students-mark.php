<?php

$studentId = $_GET["sId"];
$topicId = $_GET["tId"];

$sql = "SELECT Mark FROM feedback WHERE UserId = :sId AND TopicId = :tId";

$stmt = $db_conn->prepare($sql);
$stmt->bindParam(":sId", $studentId);
$stmt->bindParam(":tId", $topicId);
if($stmt->execute()) {
	$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$count = $stmt->rowCount();
	if ($count != 0) {
		$mark = $results[0]["Mark"];
	} else {
		$mark = -1;
	}
	echo $mark;
} else {
	echo "fail";
}
<?php

$studentId = $_GET["sId"];
$topicId = $_GET["tId"];

$sql = "SELECT Mark FROM feedback WHERE UserId = :sId AND TopicId = :tId";

$stmt = $db_conn->prepare($sql);
$stmt->bindParam(":sId", $studentId);
$stmt->bindParam(":tId", $topicId);
if($stmt->execute()) {
	$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
	echo $results[0]["Mark"];
} else {
	echo "fail";
}
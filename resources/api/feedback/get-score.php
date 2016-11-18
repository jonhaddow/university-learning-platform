<?php

$sql = $db_conn->prepare("
	SELECT Mark
	FROM feedback
	WHERE TopicId = :topicid
");
$sql->bindParam(":topicid", $_GET["topicId"]);
$sql->execute();

$results = $sql->fetchAll(PDO::FETCH_ASSOC);

$score = 0;
$count = $sql->rowCount();

foreach ($results as $row) {
	$score += $row["Mark"];
}

$score = $score / $count;

echo $score;
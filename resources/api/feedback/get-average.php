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
if ($count != 0) {
	foreach ($results as $row) {
		$score += $row["Mark"];
	}

	$score = $score / $count;

	echo $score;
} else {
	echo "No feedback given";
}
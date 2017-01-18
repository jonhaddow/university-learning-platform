<?php

if ($role != 1) {exit();}

$sql = $db_conn->prepare("
	SELECT Mark
	FROM feedback
	WHERE TopicId = :topicid
");
$sql->bindParam(":topicid", $_GET["topicId"]);

if($sql->execute()) {
	$results = $sql->fetchAll(PDO::FETCH_ASSOC);

	$score = 0;
	$count = $sql->rowCount();
	if ($count != 0) {
		foreach ($results as $row) {
			$score += $row["Mark"];
		}

		$score = $score / $count;
	} else {
		$score = -1;
	}

	echo $score;

} else {
	echo "fail";
}


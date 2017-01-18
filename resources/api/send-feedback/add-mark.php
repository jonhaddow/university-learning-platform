<?php

if ($role != 0) {exit();}

$userId = $_SESSION["userId"];
$mark = $_POST["mark"];
$topicId = $_POST["topicId"];


// Validate input
$valid = true;
if ($mark < 1 || $mark > 5) {
	$valid = false;
	die();
}

// Send SQL query to check if mark already exists.
$sql = "
	SELECT FeedbackId
    FROM feedback
    WHERE UserId = :userid
        AND TopicId = :topicid
";

// Execute statement
$stmt = $db_conn->prepare($sql);
$stmt->bindParam(":userid", $userId);
$stmt->bindParam(":topicid", $topicId);
$stmt->execute();

$response = $stmt->fetch(PDO::FETCH_ASSOC);

if ($stmt->rowCount() == 0) {

	// Insert new mark
	$sql = "
		INSERT INTO feedback (UserId, TopicId, Mark) 
		VALUES (:userid, :topicid, :mark)
	";

	$stmt = $db_conn->prepare($sql);
	$stmt->bindParam(":userid", $userId);
	$stmt->bindParam(":topicid", $topicId);
	$stmt->bindParam(":mark", $mark);

	if ($stmt->execute()) {
		$json_response["status"] = "success";
		$json_response["data"] = "null";
	} else {
		$json_response["status"] = "error";
		$json_response["message"] = "Unable to communicate with the database";
	}

} else {

	// update old mark
	$sql = "
		UPDATE feedback 
		SET Mark = :mark 
		WHERE FeedbackId = :feedbackid
	";

	$stmt = $db_conn->prepare($sql);
	$stmt->bindParam(":mark", $mark);
	$stmt->bindParam(":feedbackid", $response["FeedbackId"]);

	if ($stmt->execute()) {
		$json_response["status"] = "success";
		$json_response["data"] = "null";
	} else {
		$json_response["status"] = "error";
		$json_response["message"] = "Unable to communicate with the database";
	}
}

echo json_encode($json_response);

<?php

$userId = $_SESSION["userId"];
$topicId = $_GET["topicId"];

// Send SQL query to check if a mark exists.
$sql = "
	SELECT Mark
    FROM feedback
    WHERE UserId = :userid
        AND TopicId = :topicid
";

// Execute statement
$stmt = $db_conn->prepare($sql);
$stmt->bindParam(":userid", $userId);
$stmt->bindParam(":topicid", $topicId);
if ($stmt->execute()) {
	$json_response["status"] = "success";
} else {
	$json_response["status"] = "fail";
}

$response = $stmt->fetch(PDO::FETCH_ASSOC);

// If row exists...
if ($stmt->rowCount() == 1) {

		$json_response["data"]["mark"] = $response["Mark"];

} else {

	// If no mark exists...
		$json_response["data"]["mark"] = 0;
}

echo json_encode($json_response);
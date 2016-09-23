<?php

// include database log in details
require_once $_SERVER["DOCUMENT_ROOT"] . "/dbconfig.php";

// Send SQL query to find all topics
$sql = "
    SELECT TopicId, Name
    FROM topics
";

// Execute statement
$stmt = $db_conn->prepare($sql);
if ($stmt->execute()) {
    $json_response["status"] = "success";
} else {
    $json_response["status"] = "error";
    $json_response["message"] = "Unable to communicate with the database";
}

// Fetch all of the values of the topics
$topics = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($topics) == 0){
    $json_response["data"] = "No topics exist";
} else {
    foreach ($topics as $topic) {
        $json_response["data"][] = [
            "TopicId" => $topic["TopicId"],
            "Name" => $topic["Name"]
        ];
    }
}

// Output Json
echo json_encode($json_response);
?>
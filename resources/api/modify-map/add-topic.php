<?php

// get name of topic
$topic_name = $_POST["topicName"];

// check the length of topicName
if (strlen($topic_name) > 30) {
    $json_response["status"] = "fail";
    $json_response["data"] = "Topic name is too long.";
    echo json_encode($json_response);
    die();
}

// Send SQL query to insert new node
$sql = "
    INSERT INTO topics(Name) VALUES (:topicName)
";

// Execute statement
$stmt = $db_conn->prepare($sql);
$stmt->bindParam(":topicName", $topic_name);
try {
    if ($stmt->execute()) {
        $json_response["status"] = "success";
        $json_response["data"] = "null";
    } else {
        $json_response["status"] = "error";
        $json_response["message"] = "Unable to communicate with the database";
    }
} catch (Exception $e) {
    // if duplicate detected, handle response
    $json_response["status"] = "fail";
    $json_response["data"] = "Topic name '".$topic_name."' already exists.";
}

// Output Json
echo json_encode($json_response);

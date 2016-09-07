<?php

// include database log in details
require_once $_SERVER["DOCUMENT_ROOT"] . "/dbconfig.php";

if (!isset($_GET['child'])) {

    // If no child topic is given, then return error response
    $json_response["status"] = "failed";
    $json_response["data"]["title"] = "No child dependency given.";
    echo json_encode($json_response);
    die;
}

// Get child topic
$child = $_GET['child'];

// Send SQL query to find Parents of Topic
$sql = "
    SELECT topics.Name
    FROM
        (SELECT dependencies.ParentId
        FROM
            (SELECT topics.TopicId FROM topics WHERE Name = :child) AS T1
        INNER JOIN dependencies
        ON T1.TopicId = dependencies.ChildId) AS T2
    INNER JOIN topics
    ON T2.ParentId = topics.TopicId
";

$stmt = $db_conn->prepare($sql);
$stmt->bindParam(':child', $child);
if ($stmt->execute()) {
    $json_response["status"] = "success";
} else {
    $json_response["status"] = "error";
    $json_response["message"] = "Unable to communicate with the database";
}

// Get result as array
$parents = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

if (count($parents) == 0){
    $json_response["status"] = "success";
    $json_response["data"] = "No parents exist for the child dependency given.";
    echo json_encode($json_response);
} else {
    $json_response["status"] = "success";
    foreach ($parents as $key => $parent) {
        $json_response["data"]["parents"][] = $parent;
    }
    echo json_encode($json_response);
}
?>

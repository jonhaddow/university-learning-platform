<?php

// include database log in details
require_once $_SERVER["DOCUMENT_ROOT"] . "/dbconfig.php";

if (!isset($_GET['parent'])) {

    // If no parent topic is given, then return error response
    $json_response["status"] = "failed";
    $json_response["data"]["title"] = "No parent dependency entered.";
    echo json_encode($json_response);
    die;
}

// Get parent topic
$parent = $_GET['parent'];

// Send SQL query to find children of Topic
$sql = "
    SELECT topics.Name
    FROM
        (SELECT dependencies.ChildId
        FROM
            (SELECT topics.TopicId FROM topics WHERE Name = :parent) AS T1
        INNER JOIN dependencies
        ON T1.TopicId = dependencies.ParentId) AS T2
    INNER JOIN topics
    ON T2.ChildId = topics.TopicId
";

$stmt = $db_conn->prepare($sql);
$stmt->bindParam(':parent', $parent);
if ($stmt->execute()) {
    $json_response["status"] = "success";
} else {
    $json_response["status"] = "error";
    $json_response["message"] = "Unable to communicate with the database";
}

// Get result as array
$children = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

if (count($children) == 0){
    $json_response["data"] = "No children exist for the child dependency given.";
    echo json_encode($json_response);
} else {
    foreach ($children as $child) {
        $json_response["data"]["children"][] = $child;
    }
    echo json_encode($json_response);
}
?>

<?php

// include database log in details
require_once $_SERVER["DOCUMENT_ROOT"] . "/dbconfig.php";

// Send SQL query to find root nodes
$sql = "
    SELECT Name
    FROM topics, dependencies
    WHERE topics.TopicId NOT IN
    (
        SELECT ChildId
        FROM dependencies
    )
    GROUP BY Name
";

$stmt = $db_conn->prepare($sql);
if ($stmt->execute()) {
    $json_response["status"] = "success";
} else {
    $json_response["status"] = "error";
    $json_response["message"] = "Unable to communicate with the database";
}

/* Fetch all of the values of the first column */
$root_nodes = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

if (count($root_nodes) == 0){
    $json_response["data"] = "No root nodes exist";
    echo json_encode($json_response);
} else {
    foreach ($root_nodes as $node) {
        $json_response["data"]["root_nodes"][] = $node;
    }
    echo json_encode($json_response);
}
?>

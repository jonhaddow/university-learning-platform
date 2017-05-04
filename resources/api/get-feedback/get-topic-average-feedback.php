<?php

if ($_SESSION["role"] < 0) {
    exit();
}

// If no students are sent, return message back to javascript.
if (!isset($_GET["studentIds"])) {
    die("no-students");
}


$students = $_GET["studentIds"];

// Split up list of students
$inQuery = implode(',', $students);

// Get average feedback for each topic (only from selected students)
$sql = $db_conn->prepare("
    SELECT TopicId, AVG(Mark) AS Mark
    FROM 
    (SELECT TopicId, Mark FROM feedback WHERE UserId IN ($inQuery)) AS T1
    GROUP BY TopicId
");
if ($sql->execute()) {
    $results = $sql->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($results);
} else {
    $json_response["success"] = "fail";
    echo json_encode($json_response);
}

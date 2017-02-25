<?php

if ($_SESSION["role"] != 1) {
    exit();
}

$nameFilter = $_GET["nameFilter"];
$disabilityFilter = $_GET["disabilityFilter"];
$gradeFilter = $_GET["gradeFilter"];

// get all student Ids in $students
$stmt = $db_conn->prepare("SELECT UserId FROM users WHERE Role = 0");
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

// If a name filter exists...
if ($nameFilter != NULL) {

    // filter out all names that aren't in the filter
    $inQuery = implode(',', array_fill(0, count($nameFilter), '?'));
    $sql = $db_conn->prepare(
        "SELECT UserId FROM users WHERE UserId IN (" . $inQuery . ")"
    );
    $sql->execute($nameFilter);
    $students = $sql->fetchAll(PDO::FETCH_COLUMN, 0);
}

if ($disabilityFilter != NULL) {
    $inQuery = implode(',', $students);
    $sql = $db_conn->prepare(
        "SELECT UserId FROM users 
        WHERE UserId IN (" . $inQuery . ") AND Disability = :disability"
    );
    $sql->bindParam(":disability", $disabilityFilter);
    $sql->execute();
    $students = $sql->fetchAll(PDO::FETCH_COLUMN, 0);
}

if ($gradeFilter) { // todo Grade filter
//    $inQuery = implode(',', $students);
//    $sql = $db_conn->prepare(
//        "SELECT UserId FROM users
//        WHERE UserId IN (" . $inQuery . ") AND Grade = :grade"
//    );
//    $sql->bindParam(":grade", $gradeFilter);
//    $sql->execute();
//    $students = $sql->fetchAll(PDO::FETCH_COLUMN,0);
}

if (count($students) == 0) {
    die("no-students");
}

$inQuery = implode(',', $students);
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

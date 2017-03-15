<?php

if ($_SESSION["role"] == 0) {
    // user is student; Show only their results

    $students = [$_SESSION["userId"]];

    die(json_encode($students));

} else if ($_SESSION["role"] != 1) {
    die();
}
if (isset($_GET["nameFilter"])) {
    $nameFilter = $_GET["nameFilter"];
}
$disabilityFilter = $_GET["disabilityFilter"];
$gradeFilter = $_GET["gradeFilter"];

// get all student Ids in $students
$stmt = $db_conn->prepare("SELECT UserId FROM users WHERE Role = 0");
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

// If a name filter exists...
if (isset($nameFilter)) {

    // filter out all names that aren't in the filter
    $inQueryList = implode(',', array_fill(0, count($nameFilter), '?'));
    $sql = $db_conn->prepare(
        "SELECT UserId FROM users WHERE UserId IN (" . $inQueryList . ")"
    );
    // Execute but substitute inQueryList with student id list
    $sql->execute($nameFilter);
    $students = $sql->fetchAll(PDO::FETCH_COLUMN, 0);
}

if ($disabilityFilter != NULL) {
    $inQueryList = implode(',', $students);
    $sql = $db_conn->prepare(
        "SELECT UserId FROM users 
        WHERE UserId IN (" . $inQueryList . ") AND Disability = :disability"
    );
    $sql->bindParam(":disability", $disabilityFilter);
    $sql->execute();
    $students = $sql->fetchAll(PDO::FETCH_COLUMN, 0);
}

if ($gradeFilter != NULL) {
    $inQuery = implode(',', $students);
    $sql = $db_conn->prepare(
        "SELECT UserId FROM users
        WHERE UserId IN (" . $inQuery . ") AND Grade >= :min AND Grade <= :max"
    );
    $sql->bindParam(":min", $gradeFilter[0]);
    $sql->bindParam(":max", $gradeFilter[1]);
    $sql->execute();
    $students = $sql->fetchAll(PDO::FETCH_COLUMN, 0);
}

die(json_encode($students));
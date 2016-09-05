<?php

// include database log in details
require_once $_SERVER["DOCUMENT_ROOT"] . "/dbconfig.php";

if (!isset($_GET['child'])) {
    echo "no";
    die;
}

// Get child topic
$child = $_GET['child'];

// Send SQL query to find Parents of Topic
$sql =
"SELECT PName FROM
    (SELECT dependencies.ParentId, topics.Name AS 'PName'
    FROM dependencies
    INNER JOIN topics
    ON topics.TopicId = dependencies.ParentId)
AS parents
INNER JOIN
    (SELECT *
    FROM dependencies
    INNER JOIN topics
    ON topics.TopicId = dependencies.ChildId
    WHERE topics.Name=:child)
AS childDependants
ON parents.ParentId = childDependants.ParentId";

$stmt = $db_conn->prepare($sql);
$stmt->bindParam(':child', $child);
$stmt->execute();

// Get result
$parents = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

if (count($parents) == 0){
    echo "No dependencies exist";
} else {
    foreach ($parents as $parent) {
        echo $parent . "<br>";
    }
}
?>

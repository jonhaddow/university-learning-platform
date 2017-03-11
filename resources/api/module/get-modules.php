<?php

if ($_SESSION["role"] < 0) {
    exit();
}

// Sql to get all students names and ids
$sql = "SELECT * FROM modules";

// Execute sql
$stmt = $db_conn->prepare($sql);
$stmt->execute();

// Put results into array
$modules = $stmt->fetchAll(PDO::FETCH_ASSOC);
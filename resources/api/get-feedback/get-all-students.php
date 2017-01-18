<?php

if ($role != 1) {exit();}

// Sql to get all students names and ids
$sql = "SELECT UserId, Username FROM users WHERE Role = 0";

// Execute sql
$stmt = $db_conn->prepare($sql);
$stmt->execute();

// Put results into array
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
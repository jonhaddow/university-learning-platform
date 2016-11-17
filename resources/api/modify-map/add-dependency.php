<?php

// get parent and child names
$dependency_names = array($_POST["parent"], $_POST["child"]);
$dependency_ids = array();

// find ids for both child and parent name given
for ($i = 0; $i < 2; $i++) {

	// Send SQL query to get id
	$sql = "
        SELECT TopicId
        FROM topics
        WHERE Name = :name
    ";

	// Execute statement
	$stmt = $db_conn->prepare($sql);
	$stmt->bindParam(":name", $dependency_names[$i]);
	$stmt->execute();

	// Fetch id
	$response = $stmt->fetch(PDO::FETCH_ASSOC);
	$dependency_ids[$i] = $response["TopicId"];
}

// Check if dependency names given exist
if ($dependency_ids[0] == null || $dependency_ids[1] == null) {
	$json_response["status"] = "fail";
	$json_response["data"] = "One or more dependencies given do not exist.";
	echo json_encode($json_response);
	die();
}

/*This next section checks the new dependency doesn't
create a closed loop (e.g.
a -> b,
b -> c,
c -> a */

$isLoop = false;
$newParentId = $dependency_ids[0];
$newChildId = $dependency_ids[1];

checkParents($newParentId);

// find all parents to parent given RECURSIVE ALGORTHIM!
function checkParents($parentId)
{
	global $db_conn;
	global $newChildId;
	$sql = "
		SELECT ParentId
		FROM dependencies
		WHERE ChildId = :parentId
	";
	$stmt = $db_conn->prepare($sql);
	$stmt->bindParam(":parentId", $parentId);
	$stmt->execute();

	if ($stmt->rowCount() == 0) {
		return;
	}

	// check if any parents are equal to the child given.
	$response = $stmt->fetchAll(PDO::FETCH_ASSOC);
	foreach ($response as $item) {
		if ($newChildId == $item["ParentId"]) {
			// Found a Loop!
			$json_response["status"] = "fail";
			$json_response["data"] = "Cannot create a closed loop of dependencies.";
			echo json_encode($json_response);
			die();
		} else {
			checkParents($item["ParentId"]);
		}
	}
}

// Create insert dependency query
$sql = "
    INSERT INTO dependencies(ParentId, ChildId)
    VALUES (:parentId, :childId)
";

$stmt = $db_conn->prepare($sql);
$stmt->bindParam(":parentId", $dependency_ids[0]);
$stmt->bindParam(":childId", $dependency_ids[1]);
if ($stmt->execute()) {
	$json_response["status"] = "success";
	$json_response["data"] = "null";
} else {
	$json_response["status"] = "error";
	$json_response["message"] = "Unable to communicate with the database";
}
// Output Json
echo json_encode($json_response);
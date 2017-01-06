<?php

// Send SQL query to find all topics
$sql = "
    SELECT TopicId, Name, Description
    FROM topics
";

// Execute statement
$stmt = $db_conn->prepare($sql);
if ($stmt->execute()) {

	// Fetch all of the values of the topics
	$topics = $stmt->fetchAll(PDO::FETCH_ASSOC);

	$json_response["topics"] = null;

	// Populate topics array with topics.
	foreach ($topics as $topic) {
		$json_response["topics"][] = [
			"TopicId" => $topic["TopicId"],
			"Name" => $topic["Name"],
			"Description" => $topic["Description"]
		];
	}

	// Send SQL query to find all dependencies
	$sql = "
    SELECT ParentId, ChildId
    FROM dependencies
	";

	// Execute statement
	$stmt = $db_conn->prepare($sql);
	if ($stmt->execute()) {

		// Fetch all of the values of the parent and child dependencies
		$dependencies = $stmt->fetchAll(PDO::FETCH_ASSOC);

		if (count($dependencies) == 0) {
			$json_response["dependencies"] = null;
		} else {
			foreach ($dependencies as $dependency) {
				$json_response["dependencies"][] = [
					"ParentId" => $dependency["ParentId"],
					"ChildId" => $dependency["ChildId"]
				];
			}
		}
	} else {
		$json_response["status"] = "error";
	}
} else {
	$json_response["status"] = "error";
}


// Output Json
echo json_encode($json_response);
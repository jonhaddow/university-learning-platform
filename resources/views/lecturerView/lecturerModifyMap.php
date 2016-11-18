<!doctype html>
<html lang="en">
<head>
	<?php
	require_once COMMON_RESOURCES . "/headers.php";
	require_once VIEWS . "/lecturerView/lecturerViewHeader.php";
	?>
	<title>Lecturer Dashboard</title>

	<!-- My style -->
	<link rel="stylesheet" href="../css/dashboard.css">
	<!-- My Script -->
	<script type="text/javascript" src="../js/lecturerModifyScript.js"></script>
</head>
<body>

<nav class="navbar navbar-inverse navbar-fixed-top">
	<div class="container-fluid">
		<ul class="nav navbar-nav">
			<li>
				<a href="<?php echo $dashboard?>">View Student Feedback</a>
			</li>
			<li class="active">
				<a href="#">Modify Map</a>
			</li>
		</ul>
		<ul class="nav navbar-nav navbar-right">
			<li><a href="<?php echo $logoff ?>">Log Off</a></li>
		</ul>
	</div>
</nav>

<div class="container">
	<div class="row" id="titleBar">
		<div class="col-md-offset-2 col-md-8">
			<h1><b>Programming 1:</b> Module Topics</h1>
		</div>
	</div>
	<div class="row">
		<div id="visHolder" class="col-md-8"></div>
		<div id="sideNav" class="col-md-4">
			<div id="currentTopic">
				<h3>Highlighted Topic:</h3>
				<div id="selectedTopic">
					Please select a topic.
				</div>
				<div id="selectedTopicInfo" hidden>
					<button id="deleteTopicButton">Delete Topic</button>
				</div>
			</div>
			<div id="currentEdge">
				<h3>Highlighted Edge:</h3>
				<div id="selectedEdge">
					Please select a edge.
				</div>
				<div id="selectedEdgeInfo" hidden>
					<button id="deleteEdgeButton">Delete Edge</button>
				</div>
			</div>
			<hr>
			<div id="controls">
				<form id="newTopicForm">
					<div class="form-header"> ADD A TOPIC</div>
					<div class="form-group">
						<label for="inputNewTopic">New topic:</label>
						<input id="inputNewTopic" class="form-control" type="text">
					</div>
					<button type="submit" class="btn btn-default">Submit</button>
					<div hidden id="topicError" class="error"></div>
				</form>
				<hr>
				<form id="newDependencyForm">
					<div class="form-header"> ADD A NEW DEPENDENCY</div>
					<div class="form-group">
						<label for="parentDropdownMenuSelect">Parent Dependency</label><select
							id="parentDropdownMenuSelect" class="form-control dropdown"></select>
					</div>
					<div class="form-group">
						<label for="childDropdownMenuSelect">Child Dependency</label><select
							id="childDropdownMenuSelect" class="form-control dropdown"></select>
					</div>
					<button type="submit" class="btn btn-default">Submit</button>
					<div hidden id="dependencyError" class="error"></div>
				</form>
			</div>
		</div>
	</div>
</div>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Lecturer Dashboard</title>

	<!-- My style -->
	<link rel="stylesheet" href="style.css">
	<!-- Vis.js CSS -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/vis/4.16.1/vis.min.css">
	<!-- bootstrap CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
	      integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<!-- Optional bootstrap theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css"
	      integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
	<!-- Roboto Font -->
	<link href="https://fonts.googleapis.com/css?family=Roboto|Roboto+Condensed:300" rel="stylesheet">

	<!--    Javascript Configuration file-->
	<script src="/Project/jsConfig.js"></script>
	<!-- Jquery -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<!-- bootstrap JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
	        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
	        crossorigin="anonymous"></script>
	<!-- Vis.js -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/vis/4.16.1/vis.min.js"></script>
	<!-- My Script -->
	<script type="text/javascript" src="lecturerScript.js"></script>

</head>
<body>
<nav class="navbar navbar-default navbar-fixed-top">
	<div class="container-fluid">
		<div class="navbar-header">
			<a class="navbar-brand" href="#">Dashboard</a>
		</div>
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

<!DOCTYPE html>
<html lang="en">
<head>
	<?php require_once COMMON_RESOURCES . "/headers.php"; ?>
	<title>Lecturer Dashboard</title>

	<!-- My style -->
	<link rel="stylesheet" href="../css/dashboard.css">
	<!-- Vis.js CSS -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/vis/4.16.1/vis.min.css">

	<!-- Vis.js -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/vis/4.16.1/vis.min.js"></script>
	<!-- My Script -->
	<script type="text/javascript" src="../js/lecturerScript.js"></script>
</head>
<body>
<nav class="navbar navbar-default navbar-fixed-top">
	<div class="container-fluid">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="#">Lecturer Dashboard</a>
		</div>

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">
				<li class="active"><a href="#">Link <span class="sr-only">(current)</span></a></li>
				<li><a href="#">Link</a></li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="#">Action</a></li>
						<li><a href="#">Another action</a></li>
						<li><a href="#">Something else here</a></li>
						<li role="separator" class="divider"></li>
						<li><a href="#">Separated link</a></li>
						<li role="separator" class="divider"></li>
						<li><a href="#">One more separated link</a></li>
					</ul>
				</li>
			</ul>
			<form class="navbar-form navbar-left">
				<div class="form-group">
					<input type="text" class="form-control" placeholder="Search">
				</div>
				<button type="submit" class="btn btn-default">Submit</button>
			</form>
			<ul class="nav navbar-nav navbar-right">
				<li><a href="#">Link</a></li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="#">Action</a></li>
						<li><a href="#">Another action</a></li>
						<li><a href="#">Something else here</a></li>
						<li role="separator" class="divider"></li>
						<li><a href="#">Separated link</a></li>
					</ul>
				</li>
			</ul>
		</div><!-- /.navbar-collapse -->
	</div><!-- /.container-fluid -->
</nav>
<!--<nav class="navbar navbar-default navbar-fixed-top">-->
<!--	<div class="container-fluid">-->
<!--		<div class="navbar-header">-->
<!--			<a class="navbar-brand" href="#">Dashboard</a>-->
<!--			<a class="nav navbar-nav" href="#">View Modules</a>-->
<!--		</div>-->
<!--		<ul class="nav navbar-nav navbar-right">-->
<!--			<li><a href="--><?php //echo $logoff ?><!--">Log Off</a></li>-->
<!--		</ul>-->
<!--	</div>-->
<!--</nav>-->
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

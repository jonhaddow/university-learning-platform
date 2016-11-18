<!DOCTYPE html>
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
	<script type="text/javascript" src="../js/lecturerViewScript.js"></script>
</head>
<body>

<nav class="navbar navbar-inverse navbar-fixed-top">
	<div class="container-fluid">
		<ul class="nav navbar-nav">
			<li class="active"><a href="#">View Student Feedback</a></li>
			<li><a href="<?php echo $modify_map ?>">Modify Map</a></li>
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
		<div id="currentTopic">
			<h3>Highlighted Topic:</h3>
			<div id="selectedTopic">
				Please select a topic.
			</div>
			<div id="selectedTopicInfo" hidden>
				<div id="selectedTopicScore"></div>
			</div>
		</div>
	</div>
</div>
</body>
</html>

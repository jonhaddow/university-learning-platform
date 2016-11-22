<!DOCTYPE html>
<html lang="en">
<head>
	<?php require_once COMMON_RESOURCES . "/headers.php";
	require_once VIEWS . "/lecturerView/lecturerViewHeader.php" ?>
	<title>Student Dashboard</title>

	<!-- My style -->
	<link rel="stylesheet" href="css/dashboard.css">
	<!-- My Script -->
	<script type="text/javascript" src="../js/studentScript.js"></script>
</head>
<body>
<nav class="navbar navbar-inverse navbar-fixed-top">
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
	<div class="row">
		<div id="mainContent" class="col-md-9">
			<div>
				<h1><b>Programming 1:</b> Module Topics</h1>
			</div>
			<div id="visHolder"></div>
		</div>
		<div id="sideNav" class="col-md-3">
			<div id="currentTopic">
				<h3>Highlighted Topic:</h3>
				<div id="selectedTopic">
					Please select a topic.
				</div>
				<div id="selectedTopicControls" hidden>
					<div id="topicInfo">
					</div>
					<div id="feedback">
						<label for="myslider">Feedback:</label><br><br>
						<input id="myslider" type="text"
						       data-slider-ticks="[1, 2, 3, 4, 5]"
						       data-slider-ticks-labels='["Do not understand", "", "", "", "Fully understand"]'/>
					</div>
					<div id="completed">Feedback Sent!</div>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>

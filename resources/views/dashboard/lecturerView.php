<!DOCTYPE html>
<html lang="en">
<head>
	<?php
	require_once COMMON_RESOURCES . "/headers.php";
	require_once VIEWS . "/dashboard/dashboardHeaders.php";
	?>
	<link rel="stylesheet"
	      href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.5.0/css/bootstrap-slider.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.5.0/bootstrap-slider.min.js"></script>
	<!-- My style -->
	<link rel="stylesheet" href="/css/dashboard.css">
	<!-- My Script -->
	<script type="text/javascript" src="/js/lecturerViewScript.js"></script>
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
				<div id="selectedTopicInfo" hidden>
					<label for="myslider">Average feedback score:</label><br><br>
					<input id="myslider" type="text"
					       data-slider-ticks="[1, 5]"
					       data-slider-ticks-labels='["Do not understand", "Fully understand"]'/>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>

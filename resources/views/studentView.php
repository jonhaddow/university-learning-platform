<!DOCTYPE html>
<html lang="en">
<head>
	<?php require_once COMMON_RESOURCES . "/headers.php"; ?>
	<title>Student Dashboard</title>

	<!-- My style -->
	<link rel="stylesheet" href="../css/dashboard.css">
	<!-- Vis.js CSS -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/vis/4.16.1/vis.min.css">
	<!--Jquery UI-->
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">


	<!-- Vis.js -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/vis/4.16.1/vis.min.js"></script>
	<!--Jquery UI-->
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<!-- My Script -->
	<script type="text/javascript" src="../js/studentScript.js"></script>
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
				<div id="topicInfo">
				</div>
				<div id="feedback">
					<label for="feedbackSlider">Feedback:</label>
					<div id="slider">
						<div id="custom-handle" class="ui-slider-handle"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>

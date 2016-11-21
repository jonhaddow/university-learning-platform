<!DOCTYPE html>
<html lang="en">
<head>
	<?php require_once COMMON_RESOURCES . "/headers.php";
	require_once VIEWS . "/lecturerView/lecturerViewHeader.php"?>
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
				<div id="selectedTopicControls" hidden>
					<div id="topicInfo">
					</div>
					<div id="feedback">
						<label for="feedbackRatings">Feedback:</label>
						<div id="feedbackRatings">
							<form>
								<input type="radio" name="rating" id="rating-na" value="0" onclick="ratingClick(this)"   />
								<label for="rating-na">No feedback</label>
								<br>
								<input type="radio" name="rating" id="rating-1" value="1" onclick="ratingClick(this)"/>
								<label for="rating-1">1</label>
								<input type="radio" name="rating" id="rating-2" value="2" onclick="ratingClick(this)"/>
								<label for="rating-2">2</label>
								<input type="radio" name="rating" id="rating-3" value="3" onclick="ratingClick(this)"/>
								<label for="rating-3">3</label>
								<input type="radio" name="rating" id="rating-4" value="4" onclick="ratingClick(this)"/>
								<label for="rating-4">4</label>
								<input type="radio" name="rating" id="rating-5" value="5" onclick="ratingClick(this)"   />
								<label for="rating-5">5</label>
							</form>
						</div>
					</div>
					<div id="completed">Feedback Sent!</div>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>

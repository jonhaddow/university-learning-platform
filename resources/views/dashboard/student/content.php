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
					<div id="slider"><div id="custom-handle" class="ui-slider-handle"></div></div>
				</div>
			</div>
		</div>
	</div>
</div>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php
	require_once COMMON_RESOURCES . "/headers.php";
	require_once VIEWS . "/dashboard/dashboardHeaders.php";
	?>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.5.0/css/bootstrap-slider.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.6.2/chosen.min.css"
          integrity="sha256-QD+eN1fgrT9dm2vaE+NAAznRdtWd1JqM0xP2wkgjTSQ=" crossorigin="anonymous"/>
    <!-- My style -->
    <link rel="stylesheet" href="/css/dashboard.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.6.2/chosen.jquery.min.js"
            integrity="sha256-sLYUdmo3eloR4ytzZ+7OJsswEB3fuvUGehbzGBOoy+8=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.5.0/bootstrap-slider.min.js"></script>
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
        <div id="mainContent" class="col-sm-9">
            <div class="row">
                <div class="col-sm-6">
                    <h1><b>Programming 1:</b><br> Module Topics</h1>
                </div>
                <div class="col-sm-6">
                    <h3>View:</h3>
                    <label hidden for="studentsMenu">
                        Select a student from the list
                    </label>
                    <select id="studentsMenu" class="chosen-select">
                        <option value="785007">All Students</option>
						<?php
						for ($i = 0; $i < count($students); $i++) {
							echo "<option value='" . $students[$i]['UserId'] . "'>" . $students[$i]['Username'] .
								"</option>";
						}
						?>
                    </select>
                </div>
            </div>
            <div id="visHolder" class="row"></div>
        </div>
        <div id="sideNav" class="col-sm-3">
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

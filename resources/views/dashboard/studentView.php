<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once COMMON_RESOURCES . "/headers.php";
    require_once VIEWS . "/dashboard/dashboardHeaders.php"
    ?>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.5.0/css/bootstrap-slider.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.5.0/bootstrap-slider.min.js"></script>
    <!-- My style -->
    <link rel="stylesheet" href="/css/dashboard.css"/>
    <link rel="stylesheet" href="/css/student-dashboard.css"/>
    <!-- My Script -->
    <script type="text/javascript" src="/custom-js/studentScript.js"></script>
</head>
<body>
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
            <ul class="nav navbar-nav">
                <?php foreach ($modules as $module) {
                    if ($module["Code"] == $module_code) {
                        echo "<li class='active'><a href='/dashboard/" . $module["Code"] . "'>" . $module["Code"] . "</a></li>";
                    } else {
                        echo "<li><a href='/dashboard/" . $module["Code"] . "'>" . $module["Code"] . "</a></li>";
                    }
                } ?>
            </ul>
        <ul class="nav navbar-nav navbar-right">
            <li><a href="<?php echo LOGOFF ?>">Log Off</a></li>
        </ul>
    </div>
</nav>
<div class="container-fluid">
    <div class="row">
        <div id="mainContent" class="col-md-9">
            <div id="topPanel" class="row">
                <h1><b>Programming 1:</b> Module Topics</h1>
            </div>
            <div id="visHolder" class="row"></div>
        </div>
        <div id="sideNav" class="col-md-3">
            <div id="currentTopic">
                <h3>Highlighted Topic:</h3>
                <div id="selectedTopic">
                    Please select a topic. <?php echo $module_code; ?>
                </div>
                <div id="selectedTopicDescription"></div>
                <div id="selectedTopicControls" hidden>
                    <button id="giveFeedbackButton" class="btn btn-primary" type="button"></button>
                    <div id="feedbackSlider">
                        <div class="feedback-slider">
                            <label class="sliderLabel" for="myslider">Feedback:</label>
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
</div>

<div id="footer">
    &copy; Jonathan Haddow 2017
</div>
</body>
</html>

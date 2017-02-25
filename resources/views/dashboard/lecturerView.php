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
    <link rel="stylesheet" href="/css/lecturer-dashboard.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.6.2/chosen.jquery.min.js"
            integrity="sha256-sLYUdmo3eloR4ytzZ+7OJsswEB3fuvUGehbzGBOoy+8=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.5.0/bootstrap-slider.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
    <!-- My Script -->
    <script type="text/javascript" src="/custom-js/lecturerViewScript.js"></script>
</head>
<body>

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <ul class="nav navbar-nav">
            <li class="active"><a href="#">View Student Feedback</a></li>
            <li><a href="<?php echo MODIFY_MAP ?>">Modify Map</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li><a href="<?php echo LOGOFF ?>">Log Off</a></li>
        </ul>
    </div>
</nav>

<div class="container">
    <div class="row">
        <div id="mainContent" class="col-sm-9">
            <div id="topPanel">
                <h1><b>Programming 1:</b><br> Module Topics</h1>
                <button class="btn" id="addFilterBtn">Show Filters</button>

            </div>
            <div id="filterOptions" hidden class="row">
                <div class="col-sm-4 filterOption text-center">
                    <button id="nameFilterBtn" type="button" class="btn btn-default filter-btn">Filter by Name
                    </button>
                    <div id="nameFilterHidden" hidden>
                        <div class="containerForChosen">
                            <select id="studentsMenu" multiple class="chosen-select">
                                <?php
                                for ($i = 0; $i < count($students); $i++) {
                                    echo "<option value='" . $students[$i]['UserId'] . "'>" . $students[$i]['Username'] .
                                        "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div hidden id="studentCount"><?php echo count($students) ?></div>
                    </div>
                </div>
                <div class="col-sm-4 filterOption text-center">
                    <button id="disabilityFilterBtn" type="button" class="btn btn-default filter-btn">Filter by Disability</button>
                    <div id="disabilityFilterHidden" hidden>
                        <div class="containerForChosen">
                            <select id="disabilityMenu" class="chosen-select">
                                <option></option>
                                <option value="0">No Disability</option>
                                <option value="1">Disability</option>
                            </select>
                        </div>

                    </div>
                </div>
                <div class="col-sm-4 filterOption text-center">
                    <button type="button" class="btn btn-default filter-btn">Filter by Grade</button>
                </div>
            </div>
            <div id="noStudentToShow" class="text-center" hidden>
                <div id="noStudentToShowText">
                    No students to Show
                </div></div>
            <div id="visHolder" class="row"></div>
        </div>
        <div id="sideNav" class="col-sm-3">
            <div id="currentTopic">
                <h3>Highlighted Topic:</h3>
                <div id="selectedTopic">
                    Please select a topic.
                </div>

                <div id="selectedTopicInfo" class="feedback-section" hidden>
                    <div id="chartSpace">
                        <canvas id="myChart" width="400" height="400"></canvas>
                    </div>
                    <div id="feedbackCountSliderSpace">
                        <label>Percentage of feedback:</label>
                        <div class="progress">
                            <div id="feedbackCountSlider" class="progress-bar progress-bar-info" role="progressbar"
                                 aria-valuemin="0" aria-valuemax="100">
                                70%
                            </div>
                        </div>
                    </div>
                    <div id="averageScoreSliderSpace">
                        <label class="displayBlock" for="averageScoreSlider">Score:</label>
                        <input id="averageScoreSlider" type="text"
                               data-slider-ticks="[1, 5]"
                               data-slider-ticks-labels='["Do not understand", "Fully understand"]'/>
                    </div>
                    <div id="noFeedback">No Feedback Given</div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>

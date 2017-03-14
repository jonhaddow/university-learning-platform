<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    require_once COMMON_RESOURCES . "/headers.php";
    require_once VIEWS . "/dashboard/dashboardHeaders.php";
    ?>
    <!-- Sweet Alert -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/sweetalert2/6.2.4/sweetalert2.min.css"
          integrity="sha256-+Ri3Pm294y8V+Wp8KAUxGSsVQuqqUt1J5wqKeUWDQB0=" crossorigin="anonymous">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.5.0/css/bootstrap-slider.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.6.2/chosen.min.css"
          integrity="sha256-QD+eN1fgrT9dm2vaE+NAAznRdtWd1JqM0xP2wkgjTSQ=" crossorigin="anonymous"/>
    <!-- My style -->
    <link rel="stylesheet" href="/css/dashboard.css">
    <link rel="stylesheet" href="/css/lecturer-dashboard.css">

    <!-- Sweet Alert-->
    <script src="https://cdn.jsdelivr.net/sweetalert2/6.2.4/sweetalert2.min.js"
            integrity="sha256-Dww+oU6TBUA0Bq5awJQddFloLpNKK913ixC7UxIWzw4=" crossorigin="anonymous"></script>
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
            <li><a href="<?php echo MODIFY_MAP . "/" . $current_module["Code"]?>">Modify Map</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li><a href="<?php echo LOGOFF ?>">Log Off</a></li>
        </ul>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <div id="modulePanel" class="col-sm-1">
            <?php foreach ($modules as $module) {
                if ($module["Code"] == $current_module["Code"]) {
                    echo "<div class='active'>" . $module["Code"] . "</div>";
                } else {
                    echo "<a class='' href='". LECTURER_VIEW . "/" . $module["Code"] . "'>" . $module["Code"] . "</a>";
                }
            } ?>
            <a id="createModule" href=""><span class="glyphicon glyphicon-plus"></span> <b>New module</b></a>
        </div>
        <div id="mainContent" class="col-sm-8">
            <div class="row" id="topPanel">
                <h1><b><?php echo $current_module["Code"] . ":</b> " . $current_module["Name"] ?></h1>
                <div id="editModuleButton" class="btn">Edit <span class="glyphicon glyphicon-edit"></span></div>
                <button class="btn" id="showFilters">Show Filters <span class="glyphicon glyphicon-chevron-down"></span></button>
                <button class="btn" id="hideFilters">Hide Filters <span class="glyphicon glyphicon-chevron-up"></span></button>
                <div id="filterOptions" hidden class="row">
                    <div class="col-sm-4 text-center">
                        <button type="button" class="btn btn-default filter-btn">Filter by Name</button>
                        <div class="filter-container" hidden>
                            <div class="containerForChosen">
                                <select id="studentsMenu" multiple class="chosen-select choser">
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
                        <button type="button" class="btn btn-default filter-btn">Filter by Disability</button>
                        <div class="filter-container" hidden>
                            <div class="containerForChosen">
                                <select id="disabilityMenu" class="chosen-select choser">
                                    <option></option>
                                    <option value="0">No Disability</option>
                                    <option value="1">Disability</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 filterOption text-center">
                        <button id="gradeFilterBtn" type="button" class="btn btn-default ">Filter by Grade
                        </button>
                        <div class="filter-container" hidden>
                            <div id="gradeFilterSliderSpace">
                                <label class="gradeFilterLabel">0</label>
                                <input id="gradeFilterSlider" type="text" class="span2" value="" data-slider-min="0"
                                       data-slider-max="100" data-slider-step="10" data-slider-value="[30,70]"/>
                                <label class="gradeFilterLabel">100</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="numberStudentsShowing"></div>
            </div>
            <div id="noStudentToShow" class="text-center" hidden>
                <div id="noStudentToShowText">
                    No students to Show
                </div>
            </div>
            <div hidden id="moduleCode"><?php echo $current_module["Code"];?></div>
            <div hidden id="moduleName"><?php echo $current_module["Name"];?></div>
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
                        <label class="sliderLabel" for="averageScoreSlider">Score:</label>
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

<div id="footer">
    &copy; Jonathan Haddow 2017
</div>

</body>
</html>

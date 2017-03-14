<!doctype html>
<html lang="en">
<head>
    <?php
    require_once COMMON_RESOURCES . "/headers.php";
    require_once VIEWS . "/dashboard/dashboardHeaders.php";
    ?>
    <!-- Sweet Alert -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/sweetalert2/6.2.4/sweetalert2.min.css"
          integrity="sha256-+Ri3Pm294y8V+Wp8KAUxGSsVQuqqUt1J5wqKeUWDQB0=" crossorigin="anonymous">
    <!-- Chosen-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.6.2/chosen.min.css"
          integrity="sha256-QD+eN1fgrT9dm2vaE+NAAznRdtWd1JqM0xP2wkgjTSQ=" crossorigin="anonymous"/>
    <!-- My style -->
    <link rel="stylesheet" href="/css/dashboard.css">
    <link rel="stylesheet" href="/css/lecturer-dashboard.css">
    <!-- Sweet Alert-->
    <script src="https://cdn.jsdelivr.net/sweetalert2/6.2.4/sweetalert2.min.js"
            integrity="sha256-Dww+oU6TBUA0Bq5awJQddFloLpNKK913ixC7UxIWzw4=" crossorigin="anonymous"></script>
    <!-- Chosen -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.6.2/chosen.jquery.min.js"
            integrity="sha256-sLYUdmo3eloR4ytzZ+7OJsswEB3fuvUGehbzGBOoy+8=" crossorigin="anonymous"></script>
    <!-- My Script -->
    <script type="text/javascript" src="/custom-js/lecturerModifyScript.js"></script>
</head>
<body>

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <ul class="nav navbar-nav">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                   aria-expanded="false">Module <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <?php foreach ($modules as $module) {
                        if ($module["Code"] == $current_module["Code"]) {
                            echo "<li class='active'><a href='" . LECTURER_VIEW . "/" . $module["Code"] . "'>" . $module["Code"] . "</a></li>";
                        } else {
                            echo "<li><a href='" . LECTURER_VIEW . "/" . $module["Code"] . "'>" . $module["Code"] . "</a></li>";
                        }
                    } ?>
                    <li><a id="createModule" href="">New module...</a></li>
                </ul>
            </li>
            <li><a href="<?php echo LECTURER_VIEW . "/" . $current_module["Code"] ?>">View Student Feedback</a></li>
            <li class="active"><a href="#">Modify Map</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li><a href="<?php echo LOGOFF ?>">Log Off</a></li>
        </ul>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <div id="mainContent" class="col-md-9">
            <div class="row" id="topPanel">
                <div class="col-sm-9">
                    <h1><b><?php echo $current_module["Code"] . ":</b> " . $current_module["Name"] ?></h1>
                    <div id="editModuleButton" class="btn">Edit <span class="glyphicon glyphicon-edit"></span></div>
                </div>
                <div class="col-sm-3" id="selectedEdgeInfo" hidden>
                    <form id="deleteEdgeForm">
                        <input type="hidden" id="selectedEdgeTo" name="child">
                        <input type="hidden" id="selectedEdgeFrom" name="parent">
                        <button id="deleteEdgeButton" class="btn btn-danger">Delete Edge <span class="glyphicon glyphicon-trash"></span></button>
                    </form>
                </div>
            </div>
            <div hidden id="moduleCode"><?php echo $current_module["Code"]; ?></div>
            <div hidden id="moduleName"><?php echo $current_module["Name"];?></div>
            <div id="visHolder" class="row"></div>
        </div>
        <div id="sideNav" class="col-md-3">
            <div id="currentTopic">
                <h3>Highlighted Topic:</h3>
                <div>
                    <div id="noSelectedTopic">
                        Please select a topic.
                    </div>
                    <form id="selectedTopicForm" hidden>
                        <input type="hidden" id="selectedTopicId" name="id"/>
                        <div class="form-group">
                            <label for="selectedTopicName">Topic Name</label>
                            <input class="form-control" type="text" id="selectedTopicName" name="name"/>
                        </div>
                        <div class="form-group">
                            <label for="selectedTopicDescription">Topic Description</label>
                            <textarea class="form-control" type="text" id="selectedTopicDescription"
                                      name="description"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" id="editTopicButton">Edit Topic</button>
                        <button type="button" id="deleteTopicButton" class="btn btn-danger">Delete Topic</button>
                        <div hidden id="selectedTopicError" class="error"></div>
                    </form>
                </div>
            </div>
            <hr>
            <div id="controls">
                <form id="newTopicForm">
                    <div class="form-header"> ADD A TOPIC</div>
                    <div class="form-group">
                        <label for="inputNewTopic">New topic:</label>
                        <input id="inputNewTopic" class="form-control" type="text">
                    </div>
                    <button type="submit" class="btn btn-default">Submit</button>
                    <div hidden id="topicError" class="error"></div>
                </form>
                <hr>
                <form id="newDependencyForm">
                    <div class="form-header"> ADD A NEW DEPENDENCY</div>
                    <div class="form-group">
                        <label for="parentDropdownMenuSelect">Parent Dependency</label>
                        <select id="parentDropdownMenuSelect" class="chosen-select topicDropdown"></select>
                    </div>
                    <div class="form-group">
                        <label for="childDropdownMenuSelect">Child Dependency</label>
                        <select id="childDropdownMenuSelect" class="chosen-select topicDropdown">
                        </select>
                    </div>
                    <button type="submit" class="btn btn-default">Submit</button>
                    <div hidden id="dependencyError" class="error"></div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="footer">
    &copy; Jonathan Haddow 2017
</div>
</body>
</html>
var barChart;
var gradeFilterSlider;
var filterOnClass = "btn-success";


$(function () {

    // initialise network with current filters.
    getFilters();

    // When show filters is clicked, slide open filter options.
    $("#showFilters").click(function () {
        $("#filterOptions").slideToggle();
        $(this).hide();
        $("#hideFilters").show();
    });

    // When hide filters is clicked, hide filter options
    $("#hideFilters").click(function () {
        $("#filterOptions").slideToggle();
        $(this).hide();
        $("#showFilters").show();
    });

    // event listener for all dropdown filters
    $(".choser").chosen({width: "100%"}).change(function () {
        $("#selectedTopic").text("Please select a topic.");
        $("#selectedTopicInfo").hide();

        // initialise network with filter options.
        getFilters();
    });

    // if filter option is selected, slide option menu down.
    $(".filter-btn").click(function () {
        var filter = $(this).siblings(".filter-container");
        var dropDown = filter.find(".choser");
        filter.slideToggle();
        if ($(this).hasClass(filterOnClass)) {
            dropDown.val("");
            $(this).removeClass(filterOnClass);
            getFilters();
        } else {
            $(this).addClass(filterOnClass);
            dropDown.trigger('chosen:updated');
        }
    });

    // on click listener for the grade filter.
    $("#gradeFilterBtn").click(function () {
        var filter = $(this).siblings(".filter-container");
        filter.slideToggle();
        if ($(this).hasClass(filterOnClass)) {
            $(this).removeClass(filterOnClass);
            gradeFilterSlider = null;
            getFilters();
        } else {
            $(this).addClass(filterOnClass);
            gradeFilterSlider = $("#gradeFilterSlider").slider({
                tooltip: "always"
            }).on("slideStop", function (eventObj) {
                getFilters();
            });
            getFilters();
        }
    });
});

// Function to collect all the applied filters AND initialise network.
function getFilters() {
    if (gradeFilterSlider == null) {
        var gradeFilter = null;
    } else {
        gradeFilter = gradeFilterSlider.slider("getValue");
    }

    // request to filter with filter criteria.
    $.get(config.API_LOCATION + "get-feedback/filter-students.php", {
            nameFilter: $("#studentsMenu").val(),
            disabilityFilter: $("#disabilityMenu").val(),
            gradeFilter: gradeFilter
        }, function (result) {

            // call initialise network in dashboardGlobal.js with list of filtered students.
            initializeNetwork(JSON.parse(result));
        }
    );
}

// Function to setup network showing the feedback marks for only the students highlighted.
function setupNetwork(studentIds) {

    const topicDataset = [];

    // add topics to dataset
    if (topics) {
        for (var i = 0; i < topics.length; i++) {

            // if topic is untaught, make it a circle.
            if (topics[i].Taught === "1") {
                var color = "#888";
                var shape = "box";
            } else {
                color = "#7ac2ff";
                shape = "ellipse";
            }
            topicDataset.push({
                id: topics[i].TopicId,
                label: stringDivider(topics[i].Name),
                description: topics[i].Description,
                font: "20px arial white",
                color: {
                    background: color,
                    border: "#777",
                    highlight: {
                        background: color,
                        border: "#17a3ff"
                    }
                },
                shape: shape
            });
        }
    }

    // Send request to get average feedback scores from the list of filtered students.
    $.get(config.API_LOCATION + "get-feedback/get-topic-average-feedback.php", {
        "studentIds": studentIds
    }, function (result) {

        // If results are returned, no students were present in the list.
        if (result === "no-students") {
            $("#noStudentToShow").show();
            $("#numberStudentsShowing").hide();
            $("#visHolder").hide();
        } else {
            $("#noStudentToShow").hide();
            $("#visHolder").show();

            // Go through each average score.
            var averageScores = JSON.parse(result);
            for (i = 0; i < averageScores.length; i++) {

                // Get properties from score.
                var topicId = averageScores[i].TopicId;
                var mark = averageScores[i].Mark;

                // Go through each topic in dataset
                for (var j = 0; j < topicDataset.length; j++) {

                    // If the score's topicId matches the topic...
                    if (topicDataset[j].id === topicId) {

                        // Set the colour to match the score.
                        var color = darkColors[Math.round(mark) - 1];
                        topicDataset[j].color = {
                            background: color,
                            border: color,
                            highlight: {
                                background: color,
                                border: "#17a3ff"
                            }
                        };
                        topicDataset[j].mark = mark;
                    }
                }
            }

            // Display the number of students filtered in the top left of graph.
            $("#numberStudentsShowing").text("Showing " + studentIds.length + "/" + $("#studentCount").text() + " students").show();

            drawNetwork(topicDataset, addDependenciesToMap());
            setOnClickListeners(studentIds);
        }
    });
}

// Function to set all on click listeners within the graph.
function setOnClickListeners(studentIds) {

    // listener when node is selected
    network.on("selectNode", function (selectedNode) {

        hideFilterPanel();

        // get node label
        var nodeId = selectedNode.nodes[0];
        var nodeObj = nodes.get(nodeId);
        $("#selectedTopic").text(nodeObj.label);
        $("#selectedTopicInfo").show();

        if (!nodeObj.mark) {
            $("#noFeedback").show();
            $("#averageScoreSliderSpace").hide();
            $("#chartSpace").hide();
            $("#feedbackCountSliderSpace").hide();
        } else {
            $("#noFeedback").hide();
            $("#averageScoreSliderSpace").show();

            if (studentIds && studentIds.length == 1) {
                $("#chartSpace").hide();
                $("#feedbackCountSliderSpace").hide();
            } else {
                buildChart(nodeId, studentIds);
                $("#chartSpace").show();
                $("#feedbackCountSliderSpace").show();
            }

            drawAverageScoreSlider(nodeObj.mark);

        }
    });

    // listener when node is deselected
    network.on("deselectNode", function (selectedNode) {

        hideFilterPanel();

        // if no other node has been selected, zoom out.
        var nodeIds = selectedNode.nodes;
        if (nodeIds.length === 0) {
            $("#selectedTopic").text("Please select a topic.");
            $("#selectedTopicInfo").hide();

            if (barChart !== undefined) {
                barChart.clear();
            }
        }
    });
}

function hideFilterPanel() {
    $("#filterOptions").slideUp();
}

// Function that builds the bar chart showing student feedback.
function buildChart(topicId, studentIds) {

    // Ajax request to get all topic feedback
    // from the selected topic and the list of students given.
    $.get(config.API_LOCATION + "get-feedback/get-all-topic-feedback.php", {
        topicId: topicId,
        studentId: studentIds
    }, function (result) {
        var resultObj = JSON.parse(result);
        if (resultObj.status === "fail") {
            alert("Connection Error");
            return;
        }

        // initialise the feedback counter array
        var feedbackCounter = [0, 0, 0, 0, 0];
        for (var i = 0; i < resultObj.length; i++) {
            // for each mark, increment the corresponding array.
            feedbackCounter[resultObj[i].Mark - 1]++;
        }

        if (barChart !== undefined) {
            // clear previous instance of chart data
            barChart.destroy();
        }

        // Initialise a new chart instance.
        var ctx = $("#myChart");
        barChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ["1", "2", "3", "4", "5"],
                datasets: [{
                    label: "Student Feedback",
                    data: feedbackCounter,
                    backgroundColor: lightColors,
                    borderColor: darkColors,
                    borderWidth: 1
                }]
            },
            options: {
                legend: {
                    labels: {
                        boxWidth: 0
                    }
                },
                tooltips: {
                    callbacks: {
                        title: function (tooltipItems, data) {
                            return null;
                        },
                        label: function (tooltipItems, data) {
                            return "No. of votes: " + tooltipItems.yLabel;
                        }
                    },
                    displayColors: false
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        },
                        scaleLabel: {
                            display: true,
                            labelString: 'Number of votes'
                        }
                    }],
                    xAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'Feedback Given'
                        }
                    }]
                }
            }
        });


        // Calculate percentage of students given feedback and display on progress bar.
        var noOfFeedbackGiven = resultObj.length;
        var studentCount = $("#studentCount").text();
        var percentage = Math.round((noOfFeedbackGiven / studentIds.length) * 100);
        $("#feedbackCountSlider").css("width", percentage + "%").text(percentage + "%");
    });
}

// Function showing average student feedback score.
function drawAverageScoreSlider(mark) {
    var slider = $("#averageScoreSlider").slider({
        orientation: "vertical",
        reversed: true,
        step: "1.0",
        enabled: false,
        tooltip_position: "left",
        tooltip: "always"
    });
    slider.slider("setValue", mark);
}
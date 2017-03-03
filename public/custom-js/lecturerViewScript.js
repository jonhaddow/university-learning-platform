var barChart;
var gradeFilterSlider;
var filterOnClass = "btn-success";
var lightColors = [
    '#FFB2AA',
    '#FFD1AA',
    '#FFE3AA',
    '#FFF6AA',
    '#DCF1A1'
];
var darkColors = [
    '#AA4439',
    '#AA6C39',
    '#AA8439',
    '#AA9E39',
    '#84A136'
];

$(function () {

    getFilters();

    $("#addFilterBtn").click(function () {
        $("#filterOptions").slideToggle();
        if ($(this).text() === "Hide Filters") {
            $(this).text("Show Filters");
        } else {
            $(this).text("Hide Filters");
        }
    });

    // event listener for all dropdown filters
    $(".choser").chosen({width: "100%"}).change(function () {
        $("#selectedTopic").text("Please select a topic.");
        $("#selectedTopicInfo").hide();
        getFilters();
    });

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

    $("#gradeFilterBtn").click(function() {
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

function getFilters() {
    if (gradeFilterSlider == null) {
        var gradeFilter = null;
    } else {
        gradeFilter = gradeFilterSlider.slider("getValue");
    }

    $.get(config.API_LOCATION + "get-feedback/filter-students.php", {
            nameFilter: $("#studentsMenu").val(),
            disabilityFilter: $("#disabilityMenu").val(),
            gradeFilter: gradeFilter
        }, function (result) {
            initializeNetwork(JSON.parse(result));
        }
    );
}

function setupNetwork(studentIds) {

    // add topics to dataset
    const topicDataset = [];
    if (topics) {
        for (var i = 0; i < topics.length; i++) {
            topicDataset.push({
                id: topics[i].TopicId,
                label: stringDivider(topics[i].Name),
                description: topics[i].Description,
                font: "20px arial white",
                color: "#888"
            });
        }
    }

    $.get(config.API_LOCATION + "get-feedback/get-topic-average-feedback.php", {
        "studentIds": studentIds
    }, function (result) {
        if (result === "no-students") {
            $("#noStudentToShow").show();
            $("#numberStudentsShowing").hide();
            $("#visHolder").hide();
        } else {
            $("#noStudentToShow").hide();
            $("#visHolder").show();

            var jsonResult = JSON.parse(result);
            for (i = 0; i < jsonResult.length; i++) {
                var topicId = jsonResult[i].TopicId;
                var mark = jsonResult[i].Mark;
                for (var j = 0; j < topicDataset.length; j++) {
                    if (topicDataset[j].id === topicId) {
                        topicDataset[j].color = darkColors[Math.round(mark) - 1];
                        topicDataset[j].mark = mark;
                    }
                }
            }

            $("#numberStudentsShowing").text("Showing " + studentIds.length + "/" + $("#studentCount").text() + " students").show();

            drawNetwork(topicDataset, addDependenciesToMap());

            setOnClickListeners(studentIds);
        }
    });

}

function setOnClickListeners(studentIds) {
    // listener when node is selected
    network.on("selectNode", function (selectedNode) {

        // get node label
        var nodeId = selectedNode.nodes[0];
        var nodeObj = nodes.get(nodeId);
        $("#selectedTopic").text(nodeObj.label);
        $("#selectedTopicInfo").show();


        // focus on selected node
        network.focus(nodeId, {
            scale: 1.5,
            animation: true
        });

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

        // if no other node has been selected, zoom out.
        var nodeIds = selectedNode.nodes;
        if (nodeIds.length === 0) {
            $("#selectedTopic").text("Please select a topic.");
            $("#selectedTopicInfo").hide();
            network.fit({
                animation: true
            });

            if (barChart !== undefined) {
                barChart.clear();
            }
        }
    });
}

function buildChart(topicId, studentIds) {

    $.get(config.API_LOCATION + "get-feedback/get-all-topic-feedback.php", {
        topicId: topicId,
        studentId: studentIds
    }, function (result) {
        var resultObj = JSON.parse(result);
        if (resultObj.status === "fail") {
            alert("Connection Error");
            return;
        }

        var feedbackCounter = [0, 0, 0, 0, 0];
        for (var i = 0; i < resultObj.length; i++) {
            feedbackCounter[resultObj[i].Mark - 1]++;
        }

        if (barChart !== undefined) {
            // clear previous instance of chart data
            barChart.destroy();
        }

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
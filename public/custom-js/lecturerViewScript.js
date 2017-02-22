var barChart;

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

    initializeNetwork(null);

    // event listener for dropdown filter.
    $("#studentsMenu").chosen({width: "100%"}).change(function () {
        $("#selectedTopic").text("Please select a topic.");
        $("#selectedTopicInfo").hide();
        network.fit({
            animation: true
        });
        // Send array of student ids (null if none selected)
        initializeNetwork($(this).val());
    });
});

function setupNetwork(studentId) {

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

    if (studentId) {
        var ajaxOptions = {
            url: config.API_LOCATION + "get-feedback/get-topic-student-feedback.php",
            data: {
                "studentId": studentId
            }
        };
    } else {
        ajaxOptions = {
            url: config.API_LOCATION + "get-feedback/get-topic-average-feedback.php"
        };
    }
    $.ajax(ajaxOptions).done(function (result) {
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

        drawNetwork(topicDataset, addDependenciesToMap());

        setOnClickListeners(studentId);
    });

}

function setOnClickListeners(studentId) {
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

            if (studentId && studentId.length == 1) {
                $("#chartSpace").hide();
                $("#feedbackCountSliderSpace").hide();
            } else {
                buildChart(nodeId, studentId);
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

function buildChart(topicId, studentId) {

    $.get(config.API_LOCATION + "get-feedback/get-all-topic-feedback.php", {topicId: topicId, studentId: studentId}, function (result) {
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
        var percentage = Math.round((noOfFeedbackGiven / studentCount) * 100);
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
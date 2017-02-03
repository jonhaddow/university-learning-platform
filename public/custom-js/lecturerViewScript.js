$(function () {

    initializeNetwork(null);

    // event listener for dropdown filter.
    $("#studentsMenu").chosen({width: "100%"}).change(function () {
        $("#selectedTopic").text("Please select a topic.");
        $("#selectedTopicInfo").hide();
        network.fit({
            animation: true
        });
        var sId = $(this).val();
        if (sId >= 0) {
            initializeNetwork(sId);
        } else {
            initializeNetwork(null);
        }
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
                color: "#6C9A33"
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
            var colour;
            if (mark >= 4) {
                colour = "#6C9A33";
            } else if (mark >= 2) {
                colour = "#AA9739";
            } else if (mark >= 0) {
                colour = "#AA6239";
            } else {
                colour = "#6C9A33";
            }
            for (var j = 0; j < topicDataset.length; j++) {
                if (topicDataset[j].id === topicId) {
                    topicDataset[j].color = colour;
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
        var slider = $("#myslider").slider({
            orientation: "vertical",
            reversed: true,
            tooltip: "hide",
            step: "1.0",
            enabled: false
        });

        if (studentId) {
            if (nodeObj.mark == 0) {
                slider.slider("setValue", 1);
            } else {
                slider.slider("setValue", nodeObj.mark);
            }
        } else {
            slider.slider("setValue", nodeObj.mark);
        }

        // focus on selected node
        network.focus(nodeId, {
            scale: 1.5,
            animation: true
        });

        buildChart(nodeId);
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
        }
    });
}

function buildChart(topicId) {

    $.get(config.API_LOCATION + "get-feedback/get-all-topic-feedback.php", {topicId: topicId}, function (result) {
        var resultObj = JSON.parse(result);
        if (resultObj.status === "fail") {
            alert("Connection Error");
            return;
        }

        var feedbackCounter = [0, 0, 0, 0, 0];
        for (var i = 0; i < resultObj.length; i++) {
            feedbackCounter[resultObj[i].Mark - 1]++;
        }

        var ctx = $("#myChart");
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ["1", "2", "3", "4", "5"],
                datasets: [{
                    label: '# of Votes',
                    data: feedbackCounter,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    });
}
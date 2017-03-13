var slider;

$(function () {

    filterStudents();
});

function filterStudents() {
    $.post(config.API_LOCATION + "get-feedback/filter-students.php", function (result) {
        initializeNetwork(JSON.parse(result));
    });
}

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
                color: {
                    background: "#888",
                    border: "#777",
                    highlight: {
                        background: "#888",
                        border: "#17a3ff"
                    }
                }
            });
        }
    }

    $.get(config.API_LOCATION + "get-feedback/get-topic-average-feedback.php", {
        "studentIds": studentId
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

            drawNetwork(topicDataset, addDependenciesToMap());

            setOnClickListeners(studentId);
        }
    });
}

function setOnClickListeners() {

    // listener when node is selected
    network.on("selectNode", function (selectedNode) {

        // get node label
        var nodeId = (selectedNode.nodes)[0];
        var nodeObj = nodes.get(nodeId);
        var topicId = nodeObj.id;
        $("#selectedTopic").text(nodeObj.label);
        $("#selectedTopicDescription").text(nodeObj.description).show();
        $("#selectedTopicControls").show();
        $("#completed").hide();

        getFeedback(topicId);

        $("#giveFeedbackButton").click(function () {
            if ($(this).text() !== "Give Feedback") {
                hideSlider();
                sendMark(0, topicId);
            } else {
                showSlider(3, topicId);
                sendMark(3, topicId);
            }
        });

    });

    // listener when node is deselected
    network.on("deselectNode", function (selectedNode) {

        // if no other node has been selected, zoom out.
        var nodeIds = selectedNode.nodes;
        if (nodeIds.length === 0) {
            $("#selectedTopic").text("Please select a topic.");
            $("#selectedTopicDescription").hide();
            $("#selectedTopicControls").hide();
        }
        if (slider) {
            slider.off("slideStop");
        }
        $("#giveFeedbackButton").unbind();
    });
}

function sendMark(mark, topicId) {

    if (mark == 0) {
        $.post(config.API_LOCATION + "send-feedback/delete-mark.php", {topicId: topicId}, function () {
            $("#completed").hide();
        });
    } else {
        $.post(config.API_LOCATION + "send-feedback/add-mark.php", {mark: mark, topicId: topicId}, function () {
            // Show "completed" message
            $("#completed").show();
            filterStudents();
        });
    }

}


function getFeedback(topicId) {
    $.get(config.API_LOCATION + "send-feedback/get-mark.php", {topicId: topicId}, function (data) {
        var json = JSON.parse(data);
        var result = json.data.mark;
        if (result === 0) {
            hideSlider();
        } else {
            showSlider(result, topicId);
        }
    });
}

function hideSlider() {
    $("#giveFeedbackButton").text("Give Feedback");
    $("#feedbackSlider").hide();
}

function showSlider(result, topicId) {

    $("#giveFeedbackButton").text("Remove Feedback");
    $("#feedbackSlider").show();

    slider = $("#myslider").slider({
        reversed: true,
        orientation: "vertical",
        tooltip: "hide"
    }).on("slideStop", function (eventObj) {
        sendMark(eventObj.value, topicId);
    });

    slider.slider("setValue", result);
}
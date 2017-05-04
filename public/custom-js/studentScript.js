var slider;

$(function () {
    filterStudents();
});

function filterStudents(selectedTopicId) {
    $.post(config.API_LOCATION + "get-feedback/filter-students.php", function (result) {
        // Initialise network with a filtered list of the current student.
        initializeNetwork(JSON.parse(result),selectedTopicId);
    });
}

// Colour code network to show only the current user's feedback scores
function setupNetwork(studentId, selectedTopicId) {

    // add topics to dataset
    const topicDataset = [];
    if (topics) {
        for (var i = 0; i < topics.length; i++) {
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

    // Get current students feedback scores
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

            // Go through each student feedback.
            var studentFeedback = JSON.parse(result);
            for (i = 0; i < studentFeedback.length; i++) {
                var topicId = studentFeedback[i].TopicId;
                var mark = studentFeedback[i].Mark;

                // Go through each topic in graph
                for (var j = 0; j < topicDataset.length; j++) {

                    // If feedback is for the current topic, set color to represent score.
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

            setOnClickListeners(selectedTopicId);
        }
    });
}

// Function to initialise on click listeners for the selected topic id.
function setOnClickListeners(selectedTopicId) {

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
        $("#giveFeedbackButton").unbind();
    });


    if (selectedTopicId) {
        network.selectNodes([selectedTopicId]);
    }
}

// This function sends the feedback mark to the server.
// The parameters contain the current topic and mark to submit.
function sendMark(mark, topicId) {

    // If remove feedback button is selected, mark is equal to 0.
    if (mark == 0) {

        // Ajax request to delete the mark.
        $.post(config.API_LOCATION + "send-feedback/delete-mark.php", {topicId: topicId}, function () {
            $("#completed").hide();

            // re-initialise graph
            filterStudents(topicId);
        });
    } else {

        // Ajax request to add the mark.
        $.post(config.API_LOCATION + "send-feedback/add-mark.php", {mark: mark, topicId: topicId}, function () {

            // Show "completed" message
            $("#completed").show();

            // re-initialise graph
            filterStudents(topicId);
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

/*
This function is called when "submit feedback" is clicked.
Result parameter contains the default slider value.
TopicId parameter contains the selected topic's Id.
 */
function showSlider(result, topicId) {

    // Clear all previous slider variables
    if (slider) {
        slider.off("slideStop");
    }

    $("#giveFeedbackButton").text("Remove Feedback");
    $("#feedbackSlider").show();

    // initialise slider and set "on stop" listener
    slider = $("#myslider").slider({
        reversed: true,
        orientation: "vertical",
        tooltip: "hide"
    }).on("slideStop", function (eventObj) {
        // Submit feedback mark
        sendMark(eventObj.value, topicId);
    });

    // set slider to default value.
    slider.slider("setValue", result);
}
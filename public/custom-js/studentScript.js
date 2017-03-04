var slider;

$(function () {

    initializeNetwork();

});

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

        // focus on selected node
        network.focus(nodeId, {
            scale: 0.8,
            animation: true
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
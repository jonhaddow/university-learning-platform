var slider;

$(function () {

    initializeNetwork();

});

function setOnClickListeners() {

    // listener when node is selected
    network.on("selectNode", function (selectedNode) {

        // get node label
        var nodeIds = selectedNode.nodes;
        var nodeObj = nodes.get(nodeIds[0]);
        var topicId = nodeObj.id;
        $("#selectedTopic").text(nodeObj.label);
        $("#selectedTopicDescription").text(nodeObj.description).show();
        $("#selectedTopicControls").show();
        $("#completed").hide();

        getFeedback(topicId);

        // focus on selected node
        network.focus(nodeIds[0], {
            scale: 1.5,
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
            network.fit({
                animation: true
            });
        }
        slider.off("slideStop");
    });
}

function sendMark(mark, topicId) {

    if (mark == 0) {
        $.ajax({
            url: config.API_LOCATION + "send-feedback/delete-mark.php",
            type: "POST",
            data: {"topicId": topicId}
        }).done(function () {
            $("#completed").hide();
        })
    } else {
        $.ajax({
            url: config.API_LOCATION + "send-feedback/add-mark.php",
            type: "POST",
            data: {"mark": mark, "topicId": topicId}
        }).done(function () {
            // Show "completed" message
            $("#completed").show();
        });
    }
}


function getFeedback(topicId) {
    $.ajax({
        url: config.API_LOCATION + "send-feedback/get-mark.php?topicId=" + topicId
    }).done(function (data) {
        var json = JSON.parse(data);
        var result = json.data.mark;

        slider = $("#myslider").slider({
            reversed: true,
            orientation: "vertical",
            tooltip: "hide"
        }).on("slideStop", function (eventObj) {
            sendMark(eventObj.value, topicId);
        });

        slider.slider("setValue", result);

    });
}

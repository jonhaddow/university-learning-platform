var currentTopicId;
var topics;
var dependencies;


$("document").ready(function () {

    initializeNetwork();

    // listener when node is selected
    network.on("selectNode", function (selectedNode) {

        // get node label
        var nodeIds = selectedNode.nodes;
        var nodeObj = nodes.get(nodeIds[0]);
        currentTopicId = nodeObj.id;
        $("#selectedTopic").text(nodeObj.label);
        $("#selectedTopicControls").show();
        $("#completed").hide();

        var feedback = parseInt(getFeedback());

        var slider = $("#myslider").slider({
            reversed: true,
            orientation: "vertical",
            tooltip: "hide"
        }).on("slideStop", function (eventObj) {
            sendMark(eventObj.value);
        });

        slider.slider("setValue", feedback);

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
            $("#selectedTopicControls").hide();
            network.fit({
                animation: true
            });
        }
    });

});

function ratingClick(radio) {
    sendMark(radio.value);
}

function sendMark(mark) {

    if (mark == 0) {
        $.ajax({
            url: config.API_LOCATION + "send-feedback/delete-mark.php",
            type: "POST",
            data: {"topicId": currentTopicId},
            async: false
        }).done(function (data) {
            $("#completed").hide();
        })
    } else {
        $.ajax({
            url: config.API_LOCATION + "send-feedback/add-mark.php",
            type: "POST",
            data: {"mark": mark, "topicId": currentTopicId},
            async: false
        }).done(function (data) {
            // Show "completed" message
            $("#completed").show();
        });
    }
}


function getFeedback() {
    var result;
    $.ajax({
        url: config.API_LOCATION + "send-feedback/get-mark.php?topicId=" + currentTopicId,
        async: false
    }).done(function (data) {
        var json = JSON.parse(data);
        result = json.data.mark;
    });
    return result;
}

// This function wraps a long string around a set character limit.
function stringDivider(str, width, spaceReplacer) {
    if (str.length > width) {
        var p = width;
        for (; p > 0 && str[p] != ' '; p--) {
        }
        if (p > 0) {
            var left = str.substring(0, p);
            var right = str.substring(p + 1);
            return left + spaceReplacer + stringDivider(right, width, spaceReplacer);
        }
    }
    return str;
}

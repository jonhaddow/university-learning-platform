$("document").ready(function () {

    initializeNetwork();

    networkOptions.edges.selectionWidth = 3;

    // listener when node is selected
    network.on("selectNode", function (selectedNode) {

        // get node label
        const nodeIds = selectedNode.nodes;
        const nodeObj = nodes.get(nodeIds[0]);
        $("#selectedTopic").text(nodeObj.label);
        $("#selectedTopicInfo").show();

        // focus on selected node
        network.focus(nodeIds[0], {
            scale: 1.5,
            animation: true
        });
    });

    // listener when edge is selected
    network.on("selectEdge", function (selectedEdge) {

        // get node label
        const edgeIds = selectedEdge.edges;
        const edgeObj = edges.get(edgeIds[0]);
        const fromValue = nodes.get(edgeObj.from).label;
        const toValue = nodes.get(edgeObj.to).label;
        $("#selectedEdge").text(fromValue + " ---> " + toValue);
        $("#selectedEdgeInfo").show();

    });

    // listener when node is deselected
    network.on("deselectNode", function (selectedNode) {

        // if no other node has been selected, zoom out.
        const nodeIds = selectedNode.nodes;
        if (nodeIds.length === 0) {
            $("#selectedTopic").text("Please select a topic.");
            $("#selectedTopicInfo").hide();
            network.fit({
                animation: true
            });
        }
    });

    // listener when edge is selected
    network.on("deselectEdge", function (selectedEdge) {

        // get node label
        const edgeIds = selectedEdge.edges;
        if (edgeIds.length === 0) {
            $("#selectedEdge").text("Please select a edge.");
            $("#selectedEdgeInfo").hide();
        }

    });

    populateDependencyMenu();

    $("#deleteTopicButton").click(function () {

        $.ajax({
            url: config.API_LOCATION + "modify-map/delete-topic.php?topic=" + $("#selectedTopic").text(),
            type: "DELETE"
        }).done(function () {

            // re-initializeNetwork();
            initializeNetwork();
            populateDependencyMenu();

            $("#selectedTopic").text("Please select a topic.");
            $("#selectedTopicInfo").hide();

        });
    });

    $("#deleteEdgeButton").click(function () {

        var connectedEdges = $("#selectedEdge").text().split(" ---> ");
        var fromNode = connectedEdges[0];
        var toNode = connectedEdges[1];

        $.ajax({
            url: config.API_LOCATION + "modify-map/delete-dependency.php?parent=" + fromNode + "&child=" + toNode,
            type: "DELETE"
        }).done(function () {
            initializeNetwork();
            populateDependencyMenu();

            $("#selectedEdge").text("Please select a edge.");
            $("#selectedEdgeInfo").hide();
        });

    });

    $("#newTopicForm").submit(function () {

        const errorDiv = $("#topicError");
        errorDiv.hide();

        // send request to add topic to database
        $.ajax({
            type: "POST",
            url: config.API_LOCATION + "modify-map/add-topic.php",
            data: {topicName: $("#inputNewTopic").val()}
        }).done(function (data) {

            const jsonResponse = JSON.parse(data);
            // check response
            switch (jsonResponse.status) {
                case "success":
                    initializeNetwork();
                    populateDependencyMenu();
                    break;
                case "fail":
                    // check if failed due to length or duplication
                    if ("duplicate" in jsonResponse.data) {
                        errorDiv.show().text(jsonResponse.data.duplicate);
                    } else if ("length" in jsonResponse.data) {
                        errorDiv.show().text(jsonResponse.data.length);
                    }
                    break;
                default:
                    alert(jsonResponse.message);
            }

            // clear textbox
            $("#inputNewTopic").val("");

        });
        return false;

    });

    $("#newDependencyForm").submit(function () {

        const parent = $('#parentDropdownMenuSelect').find(":selected").text();
        const child = $('#childDropdownMenuSelect').find(":selected").text();

        if (parent === child) {
            $("#dependencyError").show().text("A topic cannot be dependent on itself.");
            return false;
        } else {
            $("#dependencyError").hide();
        }

        $.ajax({
            type: "POST",
            url: config.API_LOCATION + "modify-map/add-dependency.php",
            data: {
                parent: parent,
                child: child
            }
        }).done(function (data) {

            const jsonResponse = JSON.parse(data);
            // check response
            switch (jsonResponse.status) {
                case "success":
                    // re-initializeNetwork();
                    initializeNetwork();
                    populateDependencyMenu();

                    $("#inputParent").val("");
                    $("#inputChild").val("");
                    break;
                case "fail":
                    $("#dependencyError").show().text(jsonResponse.data);
                    break;
                default:
                    alert(jsonResponse.message);
            }

        });
        return false;
    });
});

function populateDependencyMenu() {

    // Clear current items in menus
    $('.dropdown').children().remove();
    // $('#childDropdownMenuSelect').children().remove();

    // populate with topic names
    for (var i = 0; i < topics.length; i++) {
        $("#parentDropdownMenuSelect").append("<option value='" + topics[i].TopicId + "'>" + topics[i].Name + "</option>");
        $("#childDropdownMenuSelect").append("<option value='" + topics[i].TopicId + "'>" + topics[i].Name + "</option>");
    }
}

// This function wraps a long string around a set character limit.
function stringDivider(str, width, spaceReplacer) {
    if (str.length > width) {
        var p = width;
        for (; p > 0 && str[p] != ' '; p--) {
        }
        if (p > 0) {
            const left = str.substring(0, p);
            const right = str.substring(p + 1);
            return left + spaceReplacer + stringDivider(right, width, spaceReplacer);
        }
    }
    return str;
}

$("document").ready(function () {

    updateUI();

    $("#selectedTopicForm").submit(function (e) {
        var errorDiv = $("#selectedTopicError");
        errorDiv.hide();

        var newName = $("#selectedTopicName").val();
        if (newName === '') {
            alert("Title can't be empty");
        } else {
            $.ajax({
                url: config.API_LOCATION + "modify-map/edit-topic.php",
                data: $("#selectedTopicForm").serialize(),
                type: "POST"
            }).done(function (result) {

                var obj = JSON.parse(result);

                if (obj.status === "fail") {
                    errorDiv.show();
                    errorDiv.text(obj.data);
                } else {
                    // re-initializeNetwork();
                    updateUI();
                    $("#noSelectedTopic").show();
                    $("#selectedTopicForm").hide();
                }



            });
        }
        e.preventDefault();
    });

    $("#deleteTopicButton").click(function () {

        $.ajax({
            url: config.API_LOCATION + "modify-map/delete-topic.php",
            data: {
                topic: $("#selectedTopicName").val()
            },
            type: "POST"
        }).done(function () {

            // re-initializeNetwork();
            updateUI();

            $("#selectedTopic").text("Please select a topic.");
            $("#selectedTopicInfo").hide();

        });
    });

    $("#deleteEdgeForm").submit(function (e) {

        $.ajax({
            url: config.API_LOCATION + "modify-map/delete-dependency.php",
            data: $("#deleteEdgeForm").serialize(),
            type: "POST"
        }).done(function () {
            updateUI();
            $("#selectedEdgeInfo").hide();
        });

        e.preventDefault();
    });

    $("#newTopicForm").submit(function () {

        var errorDiv = $("#topicError");
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
                    updateUI();
                    break;
                case "fail":
                    // check if failed due to length or duplication
                    errorDiv.show().text(jsonResponse.data);
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
                    updateUI();

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

function updateUI() {
    networkOptions.edges.selectionWidth = 1;
    networkOptions.edges.color.highlight = "#ff0007";
    initializeNetwork();
    setOnClickListeners();
    populateDependencyMenu();
}

function setOnClickListeners() {

    // listener when node is selected
    network.on("selectNode", function (selectedNode) {

        // get node label
        var selectedNodeId = (selectedNode.nodes)[0];
        const nodeObj = nodes.get(selectedNodeId);

        $("#noSelectedTopic").hide();

        // Show form and fill in topic details
        $("#selectedTopicForm").show();
        $("#selectedTopicId").val(nodeObj.id);
        $("#selectedTopicName").val(nodeObj.label);
        $("#selectedTopicDescription").val(nodeObj.description);

        // focus on selected node
        network.focus(selectedNodeId, {
            scale: 1.5,
            animation: true
        });
    });

    // listener when edge is selected
    network.on("selectEdge", function (selectedEdge) {

        // get node names connected to edge
        const edgeIds = selectedEdge.edges;
        const edgeObj = edges.get(edgeIds[0]);
        const fromValue = nodes.get(edgeObj.from).label;
        const toValue = nodes.get(edgeObj.to).label;

        $("#selectedEdgeTo").val(toValue);
        $("#selectedEdgeFrom").val(fromValue);

        $("#selectedEdgeInfo").show();
    });

    // listener when node is deselected
    network.on("deselectNode", function (selectedNode) {

        // if no other node has been selected, zoom out.
        const nodeIds = selectedNode.nodes;
        if (nodeIds.length === 0) {
            $("#noSelectedTopic").show();
            $("#selectedTopicForm").hide();
            network.fit({
                animation: true
            });
        }
    });

    // listener when edge is selected
    network.on("deselectEdge", function (selectedEdge) {

        const edgeIds = selectedEdge.edges;
        if (edgeIds.length === 0) {
            $("#selectedEdgeInfo").hide();
        }

    });
}

function populateDependencyMenu() {

    // Clear current items in menus
    $('.dropdown').children().remove();


    // populate with topic names
    if (topics) {
        for (var i = 0; i < topics.length; i++) {
            $("#parentDropdownMenuSelect").append("<option value='" + topics[i].TopicId + "'>" + topics[i].Name + "</option>");
            $("#childDropdownMenuSelect").append("<option value='" + topics[i].TopicId + "'>" + topics[i].Name + "</option>");
        }
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

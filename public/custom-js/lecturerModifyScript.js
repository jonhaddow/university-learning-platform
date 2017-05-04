$(function () {

    networkOptions.edges.selectionWidth = 1;
    networkOptions.edges.color.highlight = "#ff0007";

    // Call initialise network from dashboardGlobal.js
    initializeNetwork();

    // If a topic form is editted...
    $("#selectedTopicForm").submit(function (e) {
        var errorDiv = $("#selectedTopicError");
        errorDiv.hide();

        var newName = $("#selectedTopicName").val();

        // Validate input (client-side)
        if (newName === '') {
            alert("Title can't be empty");
        } else {

            // Send request to edit topic.
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

                    // if successful, re-initializeNetwork();
                    initializeNetwork();
                    $("#noSelectedTopic").show();
                    $("#selectedTopicForm").hide();
                }
            });
        }

        // prevent default form submission.
        e.preventDefault();
    });

    // If delete topic button is clicked...
    $("#deleteTopicButton").click(function () {

        // Send request to delete the topic.
        $.ajax({
            url: config.API_LOCATION + "modify-map/delete-topic.php",
            data: {
                topic: $("#selectedTopicName").val()
            },
            type: "POST"
        }).done(function () {

            // re-initializeNetwork();
            initializeNetwork();
            $("#noSelectedTopic").show();
            $("#selectedTopicForm").hide();

        });
    });

    // If delete dependency is clicked.
    $("#deleteEdgeForm").submit(function (e) {

        // Send request to delete dependency.
        $.post(config.API_LOCATION + "modify-map/delete-dependency.php", $("#deleteEdgeForm").serialize(),
            function (result) {
                if (JSON.parse(result).status == "error") {
                    alert("Error connecting to database.");
                }

                // re-initialise network
                initializeNetwork();
                $("#selectedEdgeInfo").hide();
            });

        e.preventDefault();
    });

    // If new topic button is clicked.
    $("#newTopicForm").submit(function () {

        var errorDiv = $("#topicError");
        errorDiv.hide();

        // send request to add topic to database
        $.ajax({
            type: "POST",
            url: config.API_LOCATION + "modify-map/add-topic.php",
            data: {
                topicName: $("#inputNewTopic").val(),
                moduleCode: $("#moduleCode").text(),
                taught: $("#taughtCheckbox").is(":checked")
            }
        }).done(function (data) {

            const jsonResponse = JSON.parse(data);
            // check response
            switch (jsonResponse.status) {
                case "success":
                    initializeNetwork();
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

    // If new dependency button is clicked.
    $("#newDependencyForm").submit(function () {

        const parent = $('#parentDropdownMenuSelect').find(":selected").text();
        const child = $('#childDropdownMenuSelect').find(":selected").text();

        // Validate (client-side)
        if (parent === child) {
            $("#dependencyError").show().text("A topic cannot be dependent on itself.");
            return false;
        } else {
            $("#dependencyError").hide();
        }

        // Send request to add new dependency
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

// Function to initialise on click listeners for graph objects.
function setOnClickListeners() {

    // listener when node is selected
    network.on("selectNode", function (selectedNode) {

        // get node label
        var selectedNodeId = (selectedNode.nodes)[0];
        const nodeObj = nodes.get(selectedNodeId);

        $("#noSelectedTopic").hide();

        // Show form and fill in topic details
        $("#selectedTopicForm").fadeIn();
        $("#selectedTopicId").val(nodeObj.id);
        $("#selectedTopicName").val(nodeObj.label);
        $("#selectedTopicDescription").val(nodeObj.description);

    });

    // listener when edge is selected
    network.on("selectEdge", function (selectedEdge) {

        // get node names connected to edge
        const edgeIds = selectedEdge.edges;
        const edgeObj = edges.get(edgeIds[0]);
        const fromValue = nodes.get(edgeObj.from).id;
        const toValue = nodes.get(edgeObj.to).id;

        $("#selectedEdgeTo").val(toValue);
        $("#selectedEdgeFrom").val(fromValue);

        $("#selectedEdgeInfo").show();
    });

    // listener when node is deselected
    network.on("deselectNode", function (selectedNode) {

        // if no other node has been selected, zoom out.
        const nodeIds = selectedNode.nodes;
        if (nodeIds.length === 0) {
            $("#selectedTopicForm").fadeOut(function () {
                $("#noSelectedTopic").fadeIn();
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

    populateDependencyMenu();
}

// Function to update the list of dependency in the dependency form.
function populateDependencyMenu() {

    // Clear current items in menus
    $('.topicDropdown').children().remove();

    var parent = $("#parentDropdownMenuSelect");
    var child = $("#childDropdownMenuSelect");

    // populate with topic names
    if (topics) {
        for (var i = 0; i < topics.length; i++) {
            parent.append("<option value='" + topics[i].TopicId + "'>" + topics[i].Name + "</option>");
            child.append("<option value='" + topics[i].TopicId + "'>" + topics[i].Name + "</option>");
        }
    }

    parent.chosen({width: "100%"}).trigger("chosen:updated");
    child.chosen({width: "100%"}).trigger("chosen:updated");
}
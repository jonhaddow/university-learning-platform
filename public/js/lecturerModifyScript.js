$("document").ready(function () {

    updateUI();

    $("#editTopicButton").click(function () {
        var titleText = $("#selectedTopic").text();
        swal({
            titleText: 'Topic Name',
            inputValue: titleText,
            input: 'text',
            showCancelButton: true,
            confirmButtonText: 'Update',
            showLoaderOnConfirm: true,
            preConfirm: function (newTitle) {
                return new Promise(function (resolve, reject) {
                    if (newTitle === '') {
                        reject("Title can't be empty")
                    } else {
                        resolve()
                    }

                })
            },
            allowOutsideClick: false
        }).then(function (newTitle) {
            $.ajax({
                url: config.API_LOCATION + "modify-map/edit-topic.php",
                data: {
                    topicId: selectedNodeId,
                    topicName: newTitle
                },
                type: "POST"
            }).done(function(result) {
                swal({
                    type: 'success',
                    title: 'Title Updated!'
                });
                // re-initializeNetwork();
                updateUI();
            });

        });


    })
    ;

    $("#deleteTopicButton").click(function () {

        $.ajax({
            url: config.API_LOCATION + "modify-map/delete-topic.php",
            data: {
                topic: $("#selectedTopic").text()
            },
            type: "DELETE"
        }).done(function () {

            // re-initializeNetwork();
            updateUI();

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
            updateUI();

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
                    updateUI();
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
    networkOptions.edges.selectionWidth = 3;
    initializeNetwork();
    setOnClickListeners();
    populateDependencyMenu();
}

function setOnClickListeners(){

    // listener when node is selected
    network.on("selectNode", function (selectedNode) {

        // get node label
        selectedNodeId = (selectedNode.nodes)[0];
        const nodeObj = nodes.get(selectedNodeId);
        $("#selectedTopic").text(nodeObj.label);
        $("#selectedTopicInfo").show();

        // focus on selected node
        network.focus(selectedNodeId, {
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
}

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

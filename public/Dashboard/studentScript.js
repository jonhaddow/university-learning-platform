var topics;

$("document").ready(function() {

    initializeNetwork();

    populateDependencyMenu();

});

// This function initializes the network and sets interaction listeners
function initializeNetwork() {

    // get all topic data as an jsonObj using php script
    $.ajax({
        url: API_LOCATION + "find-all-topics.php",
        async: false
    }).done(function(data) {
        var jsonObj = JSON.parse(data);
        if (jsonObj.status === "error") {
            alert(jsonObj.message);
        }
        topics = jsonObj.data;
    });

    var topicDataset = [];

    // Check if any topics exist
    if (typeof topics !== "undefined") {

        // add topics to dataset
        for (i = 0; i < topics.length; i++) {
            var id = topics[i].TopicId;
            var label = topics[i].Name;
            label = stringDivider(label, 18, "\n");
            topicDataset.push({
                id: id,
                label: label
            });
        }
    }

    // create an array of nodes from dataset
    var nodes = new vis.DataSet(topicDataset);

    // get all dependencies
    var dependencies = null;
    $.ajax({
        url: API_LOCATION + "find-all-dependencies.php",
        async: false
    }).done(function(data) {
        var jsonObj = JSON.parse(data);
        if (jsonObj.status === "error") {
            alert(jsonObj.message);
        }
        dependencies = jsonObj.data;
    });

    // push dependencies into dataset
    var dependencyDataset = [];
    for (var i = 0; i < dependencies.length; i++) {
        dependencyDataset.push({
            from: dependencies[i].ParentId,
            to: dependencies[i].ChildId
        })
    }

    // create an array with edges (dependencies)
    var edges = new vis.DataSet(dependencyDataset);

    // get the container div
    var container = document.getElementById("visHolder");

    // provide the data in the vis format
    var data = {
        nodes,
        edges: edges
    };

    // customise the options
    var options = {
        layout: {
            hierarchical: {
                enabled: true,
                nodeSpacing: 150,
                sortMethod: 'directed',
            }
        },
        nodes: {
            shadow: {
                enabled: true
            },
            shape: "box",
            labelHighlightBold: false,
            borderWidthSelected: 3,
            color: {
                highlight: {
                    background: '#FFA5A2'
                }
            }
        },
        edges: {
            arrows: {
                to: {
                    enabled: true
                }
            },
            hoverWidth: 0,
            selectionWidth: 0
        },
        interaction: {
            dragNodes: false,
            dragView: false,
            zoomView: false,
            hover: true,
            hoverConnectedEdges: false,
            selectConnectedEdges: false,
        }
    };

    // initialize the network!
    network = new vis.Network(container, data, options);

    // listener when node is selected
    network.on("selectNode", function(selectedNode) {

        // get node label
        var nodeIds = selectedNode.nodes;
        var nodeObj = nodes.get(nodeIds[0]);
        $("#selectedTopic").text(nodeObj.label);
        $("#selectedTopicInfo").show();

        // focus on selected node
        network.focus(nodeIds[0], {
            scale: 1.5,
            animation: true
        });
    });

    // listener when edge is selected
    network.on("selectEdge", function(selectedEdge) {

        // get node label
        var edgeIds = selectedEdge.edges;
        var edgeObj = edges.get(edgeIds[0]);
        var fromValue = nodes.get(edgeObj.from).label;
        var toValue = nodes.get(edgeObj.to).label;
        $("#selectedEdge").text(fromValue + " ---> " + toValue);
        $("#selectedEdgeInfo").show();

    });

    // listener when node is deselected
    network.on("deselectNode", function(selectedNode) {

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

    // listener when edge is selected
    network.on("deselectEdge", function(selectedEdge) {

        // get node label
        var edgeIds = selectedEdge.edges;
        if (edgeIds.length === 0) {
            $("#selectedEdge").text("Please select a edge.");
            $("#selectedEdgeInfo").hide();
        }

    });

    // listener when canvas is resized
    network.on("resize", function() {
        network.redraw();
    });

}

function populateDependencyMenu() {

    // Clear current items in menus
    $('.dropdown').children().remove();
    // $('#childDropdownMenuSelect').children().remove();

    // populate with topic names
    for (i = 0; i < topics.length; i++) {
        $("#parentDropdownMenuSelect").append("<option value='" + topics[i].TopicId + "'>" + topics[i].Name + "</option>");
        $("#childDropdownMenuSelect").append("<option value='" + topics[i].TopicId + "'>" + topics[i].Name + "</option>");
    }
}

// This function wraps a long string around a set character limit.
function stringDivider(str, width, spaceReplacer) {
    if (str.length > width) {
        var p = width;
        for (; p > 0 && str[p] != ' '; p--) {}
        if (p > 0) {
            var left = str.substring(0, p);
            var right = str.substring(p + 1);
            return left + spaceReplacer + stringDivider(right, width, spaceReplacer);
        }
    }
    return str;
}

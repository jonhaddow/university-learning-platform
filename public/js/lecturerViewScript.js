var currentTopicId;
var averageScore;

$(function () {

    initializeNetwork();

    // listener when node is selected
    network.on("selectNode", function (selectedNode) {

        // get node label
        var nodeIds = selectedNode.nodes;
        var nodeObj = nodes.get(nodeIds[0]);
        currentTopicId = nodeObj.id;
        $("#selectedTopic").text(nodeObj.label);
        $("#selectedTopicInfo").show();

        $.ajax({
            url: config.API_LOCATION + "feedback/get-average.php?topicId=" + currentTopicId,
            async: false
        }).done(function (data) {
            averageScore = data;
        });

        var slider = $("#myslider").slider({
            orientation: "vertical",
            reversed: true,
            tooltip: "hide",
            step: "1.0",
            enabled: false
        });
        slider.slider("setValue", averageScore);

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
            $("#selectedTopicInfo").hide();
            network.fit({
                animation: true
            });
        }
    });

});

// This function initializes the network and sets interaction listeners
function initializeNetwork() {

    // get all topic data as an jsonObj using php script
    $.ajax({
        url: config.API_LOCATION + "view-map/get-map-data.php",
        async: false
    }).done(function (data) {
        const jsonObj = JSON.parse(data);
        if (jsonObj.status === "error") {
            alert("Can't connect to database");
        }
        topics = jsonObj.topics;
        dependencies = jsonObj.dependencies;
    });

    // add topics to dataset
    const topicDataset = [];
    // add topics to dataset
    for (i = 0; i < topics.length; i++) {
        (function () {
            var id = topics[i].TopicId;
            var label = topics[i].Name;
            // Colour node based on feedback score
            $.ajax({
                url: config.API_LOCATION + "feedback/get-average.php?topicId=" + id,
                async: false
            }).done(function (data) {
                var colour;
                if (data >= 4) {
                    colour = "#6C9A33";
                } else if (data >= 2) {
                    colour = "#AA9739";
                } else if (data >= 0) {
                    colour = "#AA6239";
                } else {
                    colour = "#6C9A33";
                }
                label = stringDivider(label, 18, "\n");
                topicDataset.push({
                    id: id,
                    label: label,
                    color: colour,
                    font: "20px arial white"
                });
            });
        })();
    }

    // add dependencies into dataset
    const dependencyDataset = [];
    for (var i = 0; i < dependencies.length; i++) {
        dependencyDataset.push({
            from: dependencies[i].ParentId,
            to: dependencies[i].ChildId
        });
    }

    nodes = new vis.DataSet(topicDataset);
    edges = new vis.DataSet(dependencyDataset);

    // provide the data in the vis format
    const data = {
        nodes: nodes,
        edges: edges
    };

    // get the container div
    const container = document.getElementById("visHolder");
    // initialize the network!
    network = new vis.Network(container, data, networkOptions);

    // listener when canvas is resized
    network.on("resize", function () {
        network.redraw();
    });

}
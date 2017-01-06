var averageScore;

$(function () {

    updateGUI(null);

    $("#studentsMenu").chosen({width: "100%"}).change(function () {
        $("#selectedTopic").text("Please select a topic.");
        $("#selectedTopicInfo").hide();
        network.fit({
            animation: true
        });
        var sId = $(this).val();
        if (sId >= 0) {
            updateGUI(sId);
        } else {
            updateGUI(null);
        }
    });
});

function updateGUI(studentId) {
    initializeNetwork(studentId);
    setOnClickListeners(studentId);
}

// This function initializes the network and sets interaction listeners
function initializeNetwork(studentId) {

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
            var ajaxOptions = {};
            if (studentId) {
                ajaxOptions = {
                    url: config.API_LOCATION + "get-feedback/get-students-mark.php",
                    data: {
                        "sId": studentId,
                        "tId": id
                    },
                    async: false
                };
            } else {
                ajaxOptions = {
                    url: config.API_LOCATION + "get-feedback/get-average.php",
                    data: {"topicId": id},
                    async: false
                };
            }
            $.ajax(ajaxOptions).done(function (result) {
                var colour;
                if (result >= 4) {
                    colour = "#6C9A33";
                } else if (result >= 2) {
                    colour = "#AA9739";
                } else if (result >= 0) {
                    colour = "#AA6239";
                } else {
                    colour = "#6C9A33";
                }
                topicDataset.push({
                    id: id,
                    label: stringDivider(label, 18, "\n"),
                    color: colour,
                    font: "20px arial white",
                    mark: result
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

function setOnClickListeners(studentId) {
    // listener when node is selected
    network.on("selectNode", function (selectedNode) {

        // get node label
        var nodeIds = selectedNode.nodes;
        var nodeObj = nodes.get(nodeIds[0]);
        var currentTopicId = nodeObj.id;
        $("#selectedTopic").text(nodeObj.label);
        $("#selectedTopicInfo").show();
        var slider = $("#myslider").slider({
            orientation: "vertical",
            reversed: true,
            tooltip: "hide",
            step: "1.0",
            enabled: false
        });

        if (studentId) {
            if (nodeObj.mark == 0) {
                slider.slider("setValue", 1);
            } else {
                slider.slider("setValue", nodeObj.mark);
            }
        } else {
            slider.slider("setValue", nodeObj.mark);
        }

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
}
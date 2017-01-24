$(function () {

    updateMap(null);

    $("#studentsMenu").chosen({width: "100%"}).change(function () {
        $("#selectedTopic").text("Please select a topic.");
        $("#selectedTopicInfo").hide();
        network.fit({
            animation: true
        });
        var sId = $(this).val();
        if (sId >= 0) {
            updateMap(sId);
        } else {
            updateMap(null);
        }
    });
});

// This function gets map data
function updateMap(studentId) {

    // get all topic data as an jsonObj using php script
    $.ajax({
        url: config.API_LOCATION + "view-map/get-map-data.php"
    }).done(function (data) {
        const jsonObj = JSON.parse(data);
        if (jsonObj.status === "error") {
            alert("Can't connect to database");
        }
        topics = jsonObj.topics;
        dependencies = jsonObj.dependencies;
        setupNetwork(studentId);
    });
}

function setupNetwork(studentId) {

    // add topics to dataset
    const topicDataset = [];
    if (topics) {
        for (var i = 0; i < topics.length; i++) {
            topicDataset.push({
                id: topics[i].TopicId,
                label: stringDivider(topics[i].Name),
                description: topics[i].Description,
                font: "20px arial white",
                color: "#6C9A33"
            });
        }
    }

    var ajaxOptions = {};
    if (studentId) {
        ajaxOptions = {
            url: config.API_LOCATION + "get-feedback/get-student-feedback.php",
            data: {
                "studentId": studentId
            }
        };
    } else {
        ajaxOptions = {
            url: config.API_LOCATION + "get-feedback/get-feedback-average.php"
        };
    }
    $.ajax(ajaxOptions).done(function (result) {
        var jsonResult = JSON.parse(result);
        for (i = 0; i<jsonResult.length; i++) {
            var topicId = jsonResult[i].TopicId;
            var mark = jsonResult[i].Mark;
            var colour;
            if (mark >= 4) {
                colour = "#6C9A33";
            } else if (mark >= 2) {
                colour = "#AA9739";
            } else if (mark >= 0) {
                colour = "#AA6239";
            } else {
                colour = "#6C9A33";
            }
            for (var j = 0; j<topicDataset.length; j++) {
                if (topicDataset[j].id === topicId){
                    topicDataset[j].color = colour;
                    topicDataset[j].mark = mark;
                }
            }
        }

        // add dependencies into dataset
        const dependencyDataset = [];
        if (dependencies) {
            for (i = 0; i < dependencies.length; i++) {
                dependencyDataset.push({
                    from: dependencies[i].ParentId,
                    to: dependencies[i].ChildId
                });
            }
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

        setOnClickListeners(studentId);
    });

}

function setOnClickListeners(studentId) {
    // listener when node is selected
    network.on("selectNode", function (selectedNode) {

        // get node label
        var nodeIds = selectedNode.nodes;
        var nodeObj = nodes.get(nodeIds[0]);
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
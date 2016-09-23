$("document").ready(function() {

    // get all topic data as an jsonObj using php script
    var topics;
    $.ajax({
        url: "find-all-nodes.php",
        async: false
    }).done(function(data) {
        var jsonObj = JSON.parse(data);
        topics = jsonObj.data;
    });

    // add topics to dataset
    var topicDataset = [];
    for (var i = 0; i < topics.length; i++) {
        topicDataset.push({
            id: topics[i].TopicId,
            label: topics[i].Name
        });
    }

    // create an array of nodes from dataset
    var nodes = new vis.DataSet(topicDataset);

    var dependencies;
    $.ajax({
        url: "find-all-dependencies.php",
        async: false
    }).done( function(data) {
        var jsonObj = JSON.parse(data);
        dependencies = jsonObj.data;
    });

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
    var container = document.getElementById("myholder");

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
    }

    // initialize the network!
    var network = new vis.Network(container, data, options);

    // listener when node is selected
    network.on("selectNode", function(selectedNode) {

        // get node label
        var nodeIds = selectedNode.nodes;
        var nodeObj = nodes.get(nodeIds[0]);
        $("#response").text(nodeObj.label);

        // focus on selected node
        network.focus(nodeIds[0], {
            scale: 1.5,
            animation: true
        });
    })

    // listener when node is deselected
    network.on("deselectNode", function(selectedNode) {

        // if no other node has been selected, zoom out.
        var nodeIds = selectedNode.nodes;
        if (nodeIds.length === 0) {
            $("#response").empty();
            network.fit({
                animation: true
            });
        }
    });
});

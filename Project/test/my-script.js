$("document").ready(function() {

    // create an array of nodes from database
    var nodes = new vis.DataSet([
        { id: 1, label: 'Variables' },
        { id: 2, label: 'Booleans' },
        { id: 3, label: 'Strings' },
        { id: 4, label: 'Integers' },
        { id: 5, label: 'Process user input' },
        { id: 6, label: 'Understand Comparisons' },
        { id: 7, label: 'If/Else Statements' },
        { id: 8, label: 'Switch Statement' }
    ]);

    // create an array with edges (dependencies)
    var edges = new vis.DataSet([
        { from: 1, to: 2 },
        { from: 1, to: 3 },
        { from: 1, to: 4 },
        { from: 4, to: 5 },
        { from: 2, to: 6 },
        { from: 3, to: 6 },
        { from: 6, to: 7 },
        { from: 7, to: 8 }
    ]);

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

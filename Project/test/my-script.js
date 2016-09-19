$("document").ready(function() {

    // create an array with nodes
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

    // create an array with edges
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

    // create a network
    var container = document.getElementById('myholder');

    // provide the data in the vis format
    var data = {
        nodes: nodes,
        edges: edges
    };

    var options = {
        layout: {
            hierarchical: {
                enabled: true,
                nodeSpacing: 150,
                sortMethod: 'directed',
            }
        },
        nodes: {
            fixed: {
                x: true,
                y: true
            },
            shadow: {
                enabled: true
            },
            shape: "box"
        },
        edges: {
            arrows: {
                to: {
                    enabled: true
                }
            },
        }
    }

    // initialize your network!
    var network = new vis.Network(container, data, options);

});

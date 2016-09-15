function findRootNodes() {
    var rootNodes = null;
    $.ajax({
        url: "../find-root-nodes.php",
        async: false,
        success: function(data) {
            var jsonObj = JSON.parse(data);
            // Get array of nodes
            rootNodes = jsonObj.data.root_nodes;
        }
    });
    return rootNodes;
}

// This parent finds children of a set of parent nodes
function findNodes(parentNodes) {

    var childNodes = new Array();

    // Go through each parent node
    for (var i = 0; i < parentNodes.length; i++) {

        // Get all children from that parent
        $.ajax({
            url: "../dependency-check.php",
            async: false,
            data: { parent: parentNodes[i] },
            success: function(data) {

                // Parse Json
                var jsonObj = JSON.parse(data);
                // Get array of nodes
                var children = jsonObj.data.children;
                for (var j = 0; j < children.length; j++) {
                    // Remove duplicates
                    if ($.inArray(children[j], childNodes) === -1) {
                        childNodes.push(children[j]);
                    }
                }
            }
        });
    }

    return childNodes;
}

$("document").ready(function() {

    // Declare new graph
    var graph = new joint.dia.Graph;

    // Create paper
    var paper = new joint.dia.Paper({
        el: $('#myholder'),
        width: 2000,
        height: 4000,
        model: graph,
        gridSize: 1,
    });

    // Function to draw a rectangle
    function createRect(innerText) {

        var rect = new joint.shapes.basic.Rect({
            position: { x: 0, y: 0 },
            size: { width: 200, height: 40 },
            attrs: {
                rect: { fill: 'red', opacity: 0.5 },
                text: { text: innerText, fill: 'white' }
            }
        });
        graph.addCell(rect);
        return rect;
    }

    // Function to draw a arrow between two rects
    function createArrow(r1, r2) {

        var arrow = new joint.shapes.fsa.Arrow({
            source: { id: r1.id },
            target: { id: r2.id }
        });
        graph.addCell(arrow);
        return arrow;
    }

    // Set up arrays for each layer and each rectangle
    var layer = new Array();
    var rects = new Array();

    var row = 0;
    var continueLoop = true;

    layer[row] = findRootNodes();

    while (continueLoop) {

        // For each node, draw a rectangle
        for (var i = 0; i < layer[row].length; i++) {
            var rect = createRect(layer[row][i]);
            rect.translate(i * 300, row * 100);
            rects.push(rect);
        }

        // increment row
        row++;

        // Find all nodes in the next layer using parent nodes
        layer[row] = findNodes(layer[row - 1]);

        if (layer[row].length === 0) {
            continueLoop = false;
        }


    }

    // Disable interaction
    paper.$el.css('pointer-events', 'none');


});

$("document").ready(function() {
    var graph = new joint.dia.Graph;

    // Create paper
    var paper = new joint.dia.Paper({
        el: $('#myholder'),
        width: 600,
        height: 1200,
        model: graph,
        gridSize: 1,
    });

    function createRect(innerText) {
        // Create rectangle
        var rect = new joint.shapes.basic.Rect({
            position: { x: 0, y: 0 },
            size: { width: 120, height: 40 },
            attrs: {
                rect: { fill: 'red', opacity: 0.5 },
                text: { text: innerText, fill: 'white' }
            }
        });
        graph.addCell(rect);
        return rect;
    }

    function createArrow(r1, r2) {
        // Draw a arrow between two rects
        var arrow = new joint.shapes.fsa.Arrow({
            source: { id: r1.id },
            target: { id: r2.id }
        });
        graph.addCell(arrow);
        return arrow;
    }

    var rect = createRect("My First Box");
    var rect2 = createRect("Second Box");
    var rect3 = createRect("Third Box");

    rect2.translate(300);
    rect3.translate(150);

    var link = createArrow(rect2, rect);

    // Disable interaction
    paper.$el.css('pointer-events', 'none');

    $("#enterButton").click(function() {
        var input = $("#inputModule").val();
        $.get("../dependency-check.php", { child: input }, function(data) {
            var jsonObj = JSON.parse(data);
            var parents = jsonObj.data.parents;
            for (var i = 0; i < parents.length; i++) {
                alert(parents[i]);
            }
        });
        rect2.attr({
            text: {
                text: input
            }
        });
    });

});

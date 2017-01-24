// This function wraps a long string around a set character limit.
function stringDivider(str) {
    var width = 18;
    var spaceReplacer = "\n";
    if (str.length > width) {
        var p = width;
        for (; p > 0 && str[p] != ' '; p--) {
        }
        if (p > 0) {
            var left = str.substring(0, p);
            var right = str.substring(p + 1);
            return left + spaceReplacer + stringDivider(right);
        }
    }
    return str;
}

var topics = [];
var dependencies = [];
var network;
var nodes;
var edges;

// This function initializes the network and sets interaction listeners
function initializeNetwork() {

    $.get(config.API_LOCATION + "view-map/get-map-data.php", function (result) {
        const jsonObj = JSON.parse(result);
        if (jsonObj.status === "error") {
            alert("Can't connect to database");
        }
        topics = jsonObj.topics;
        dependencies = jsonObj.dependencies;

        // add  topics to dataset
        const topicDataset = [];
        if (topics) {
            for (i = 0; i < topics.length; i++) {
                var id = topics[i].TopicId;
                var name = topics[i].Name;
                name = stringDivider(name);
                topicDataset.push({
                    id: id,
                    label: name,
                    description: topics[i].Description
                });
            }
        }

        // add dependencies into dataset
        const dependencyDataset = [];
        if (dependencies) {
            for (var i = 0; i < dependencies.length; i++) {
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

        setOnClickListeners();
    });

}
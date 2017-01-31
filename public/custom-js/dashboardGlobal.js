var topics = [];
var dependencies = [];
var network;
var nodes;
var edges;

// This function initializes the network and sets interaction listeners
function initializeNetwork(studentId) {

    $.get(config.API_LOCATION + "view-map/get-map-data.php", function (result) {
        const jsonObj = JSON.parse(result);
        if (jsonObj.status === "error") {
            alert("Can't connect to database");
        }
        topics = jsonObj.topics;
        dependencies = jsonObj.dependencies;

        // if this network is filtered for an individual network, setup network separately.
        if (typeof setupNetwork === "function") {
            setupNetwork(studentId);
        } else {
            drawNetwork(addTopicsToMap(),addDependenciesToMap());
            setOnClickListeners();
        }
    });

}

function addTopicsToMap() {
    // add topics to the dataset
    var topicDataset = [];
    if (topics) {
        for (var i = 0; i < topics.length; i++) {
            topicDataset.push({
                id: topics[i].TopicId,
                label: stringDivider(topics[i].Name),
                description: topics[i].Description
            });
        }
    }
    return topicDataset;
}

function addDependenciesToMap() {
    // add dependencies into the dataset
    const dependencyDataset = [];
    if (dependencies) {
        for (var i = 0; i < dependencies.length; i++) {
            dependencyDataset.push({
                from: dependencies[i].ParentId,
                to: dependencies[i].ChildId
            });
        }
    }
    return dependencyDataset;
}

// function to collect topic and dependency data and draw to the network.
function drawNetwork(topicsDataset, dependenciesDataset) {

    nodes = new vis.DataSet(topicsDataset);
    edges = new vis.DataSet(dependenciesDataset);

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
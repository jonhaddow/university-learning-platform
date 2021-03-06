var topics = [];
var dependencies = [];
var network;
var nodes;
var edges;
var lightColors = [
    '#FFB2AA',
    '#FFD1AA',
    '#FFE3AA',
    '#FFF6AA',
    '#DCF1A1'
];
var darkColors = [
    '#AA4439',
    '#AA6C39',
    '#AA8439',
    '#AA9E39',
    '#84A136'
];

$(function () {

    // When edit module button is clicked...
    $("#editModuleButton").click(function () {

        // Bring up dialog box.
        swal({
            title: 'Edit module',
            html: '' +
            '<label>Module Code: </label>' +
            '<input id="newModuleCode" class="swal2-input" value="' + $("#moduleCode").text() + '">' +
            '<label>Module Name: </label>' +
            '<input id="newModuleName" class="swal2-input" value="' + $("#moduleName").text() + '">',
            showCloseButton: true,
            showCancelButton: true,
            confirmButtonText: 'Edit',
            cancelButtonText: 'Delete',
            cancelButtonColor: '#e50b19',
            showLoaderOnConfirm: true,
            preConfirm: function () {
                return new Promise(function (resolve, reject) {

                    // Check that new name and code is valid.
                    $.post(config.API_LOCATION + "module/edit-module.php", {
                        oldModuleCode: $("#moduleCode").text(),
                        moduleCode: $("#newModuleCode").val(),
                        moduleName: $("#newModuleName").val()
                    }, function (result) {
                        var jsonResult = JSON.parse(result);
                        if (jsonResult.status === "error") {
                            if (jsonResult.message === "length") {
                                reject("Please enter the Module code and name");
                            } else {
                                reject("Module Code already exists");
                            }
                        } else {
                            resolve();
                        }
                    });
                })
            },
            allowOutsideClick: false
        }).then(function () {

            // Bring up success dialog box.
            swal({
                type: 'success',
                title: 'Module Updated'
            }).then(function () {
                location.reload();
            });
        }, function (dismiss) {

            // If delete button is clicked...
            if (dismiss === "cancel") {

                // Delete module.
                $.post(config.API_LOCATION + "module/delete-module.php", {
                    moduleCode: $("#moduleCode").text()
                }, function () {

                    // Confirmation dialog on deletion of module.
                    swal({
                        title: 'Module Deleted',
                        type: 'error'
                    }).then(function () {
                        location.reload();
                    });
                });
            }
        });
    });

    // On create module click...
    $("#createModule").click(function (e) {
        e.preventDefault();
        createNewModule()
    });
});

// Function to create a new module.
function createNewModule() {

    // Bring up create module dialog.
    swal({
        title: 'Create new module',
        html: '' +
        '<label>Module Code: </label>' +
        '<input id="newModuleCode" class="swal2-input">' +
        '<label>Module Name: </label>' +
        '<input id="newModuleName" class="swal2-input">',
        showCancelButton: true,
        confirmButtonText: 'Create',
        showLoaderOnConfirm: true,
        preConfirm: function () {
            return new Promise(function (resolve, reject) {

                // Send request to create module.
                $.post(config.API_LOCATION + "module/add-module.php", {
                    moduleCode: $("#newModuleCode").val(),
                    moduleName: $("#newModuleName").val()
                }, function (result) {
                    var jsonResult = JSON.parse(result);
                    if (jsonResult.status === "error") {
                        if (jsonResult.message === "length") {
                            reject("Please enter the Module code and name");
                        } else {
                            reject("Module Code already exists");
                        }
                    } else {
                        resolve();
                    }
                });

            })
        },
        allowOutsideClick: false
    }).then(function () {

        // Confirmation dialog on success.
        swal({
            type: 'success',
            title: 'New module added!'
        }).then(function () {

            // reload page.
            location.reload();
        });
    });
}

// This function initializes the network and sets interaction listeners
function initializeNetwork(studentIds, selectedTopicId) {
    var moduleCode = $("#moduleCode").text();

    // Get the map data. (topics and dependencies related to this module.
    $.get(config.API_LOCATION + "view-map/get-map-data.php", {moduleCode: moduleCode}, function (result) {
        const jsonObj = JSON.parse(result);
        if (jsonObj.status === "error") {
            alert("Can't connect to database");
        }

        topics = jsonObj.topics;
        dependencies = jsonObj.dependencies;

        // if this network is filtered for an specific group, setup network separately.
        if (typeof setupNetwork === "function") {
            setupNetwork(studentIds, selectedTopicId);
        } else {
            drawNetwork(addTopicsToMap(), addDependenciesToMap());
            setOnClickListeners();
        }
    });

}

// Convert list of topic into topic dataset.
function addTopicsToMap() {
    // add topics to the dataset
    var topicDataset = [];
    if (topics) {
        for (var i = 0; i < topics.length; i++) {

            // If topic is taught, make it circular.
            if (topics[i].Taught === "1") {
                var shape = "box";
            } else {
                shape = "ellipse";
            }
            topicDataset.push({
                id: topics[i].TopicId,
                label: stringDivider(topics[i].Name),
                description: topics[i].Description,
                shape: shape
            });
        }
    }
    return topicDataset;
}

// Convert list of dependencies into dependency dataset.
function addDependenciesToMap() {
    // add dependencies into the dataset
    const dependencyDataset = [];
    if (dependencies) {
        for (var i = 0; i < dependencies.length; i++) {

            // If dependency is taught, make it dashed.
            if (dependencies[i].Taught == "0") {
                var dashLines = {
                    dashes: true
                };
            } else {
                dashLines = {};
            }
            dependencyDataset.push(Object.assign({
                from: dependencies[i].ParentId,
                to: dependencies[i].ChildId
            }, dashLines));
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

    // initialize the network with global network options
    network = new vis.Network(container, data, networkOptions);

    // listener when canvas is resized
    network.on("resize", function () {
        network.redraw();
    });
}

// This function wraps a long string around a set character limit.
// It stops a long string from filling the module map.
function stringDivider(str) {
    var width = 18;
    var spaceReplacer = " \n";
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
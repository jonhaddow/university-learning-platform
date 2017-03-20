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

$(function() {

    $("#editModuleButton").click(function() {
        swal({
            title: 'Edit module',
            html: '' +
            '<label>Module Code: </label>' +
            '<input id="newModuleCode" class="swal2-input" value="'+$("#moduleCode").text()+'">' +
            '<label>Module Name: </label>' +
            '<input id="newModuleName" class="swal2-input" value="'+$("#moduleName").text()+'">',
            showCloseButton: true,
            showCancelButton: true,
            confirmButtonText: 'Edit',
            cancelButtonText: 'Delete',
            cancelButtonColor: '#e50b19',
            showLoaderOnConfirm: true,
            preConfirm: function () {
                return new Promise(function (resolve, reject) {
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
            swal({
                type: 'success',
                title: 'Module Updated'
            }).then(function() {
                location.reload();
            });
        }, function(dismiss) {
            if (dismiss === "cancel") {
                $.post(config.API_LOCATION + "module/delete-module.php", {
                    moduleCode: $("#moduleCode").text()
                }, function() {
                    swal({
                        title: 'Module Deleted',
                        type: 'error'
                    }).then(function() {
                        location.reload();
                    });
                });
            }
        });
    });

    $("#createModule").click(function(e) {
        e.preventDefault();
        createNewModule()
    });
});

function createNewModule() {
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
        swal({
            type: 'success',
            title: 'New module added!'
        }).then(function() {
            location.reload();
        });
    });
}

// This function initializes the network and sets interaction listeners
function initializeNetwork(studentIds, selectedTopicId) {

    var moduleCode = $("#moduleCode").text();
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
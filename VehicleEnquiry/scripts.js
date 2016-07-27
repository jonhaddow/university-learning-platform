var getVehicleDataPhpFile = "php/get-vehicle-data.php";

function getVehicleInfo() {
    var vrn = $("#inputVrn").val();
    var make = $("#inputMake").val();

    if (vrn === "" || make === "") {
        return;
    }

    $("#response").show();

    $.get(getVehicleDataPhpFile + "?inputVrn=" + vrn + "&inputMake=" + make, function(result) {
        if (result === "Vehicle does not exist.") {
            $("#apiResponseField").val(result);
        } else {
            var pretty = JSON.stringify(JSON.parse(result), null, 2);
            $("#apiResponseField").val(pretty);

        }
    });
}

function loadTab1() {

    $("#response").hide();

    $("#getVehicle").click(function() {
        getVehicleInfo();
    });

    $("#copy-to-clipboard").click(function() {
        var text2Copy = $("#apiResponseField").val();
        $("#apiResponseField").select();
    });
}

function submitVehicle() {

}

function loadTab2() {

    $.get(getVehicleDataPhpFile, function(result) {
        var json = JSON.parse(result);
        for (var x = 0; x < json.length; x++) {
            $("#submit-vehicle-form").append(
                "<div><div class='mdl-textfield mdl-js-textfield mdl-textfield--floating-label'>" +
                "<input class='mdl-textfield__input enterText' required type='text' name='input-" +
                json[x] +
                "'>" +
                "<label class='mdl-textfield__label'>" + json[x] + "</label></div></div>"
            );
        }

        // Update material design dynamically
        componentHandler.upgradeDom();
    });

    $("#submit-vehicle-button").click(function() {
        submitVehicle();
    });
}

$(function() {

    loadTab1();

    loadTab2();

});

var getVehicleDataPhpFile = "php/get-vehicle-data.php";

var validVrn = true;
var validMake = true;

function validationCheck() {
    
    if ($("#inputVrn").val().length === 0) {
        validVrn = false;
        $("#divVrn")
            .addClass("has-error")
    } else {
        validVrn = true;
        $("#divVrn").removeClass("has-error");
    }
    
    if ($("#inputMake").val().length === 0) {
        validMake = false;
        $("#divMake")
            .addClass("has-error")
    } else {
        validMake = true;
        $("#divMake").removeClass("has-error");
    }
    
    if (validVrn && validMake) {
        return true;
    } else {
        return false;
    }
    
}

function getVehicleInfo() {
    var vrn = $("#inputVrn").val();
    var make = $("#inputMake").val();

    if (vrn === "" || make === "") {
        return;
    }

    $.get(getVehicleDataPhpFile + "?inputVrn=" + vrn + "&inputMake=" + make, function (result) {
        if (result === "Vehicle does not exist.") {
            $("#responseTextarea").val(result);
        } else {
            var pretty = JSON.stringify(JSON.parse(result), null, 2);
            $("#responseTextarea").val(pretty);
        }
    });
}

$(function () {
    $("#response-container").hide();

    $("#getVehicle").click(function () {
        if (validationCheck()) {
            getVehicleInfo();
            $("#response-container").show();
        } else {
            
        }
        
    });

    $("#selectAllBtn").click(function () {
        $("#responseTextarea").select();
    });
});
var getVehicleDataPhpFile = "../php/get-vehicle-data.php";

$(function() {
    $.get(getVehicleDataPhpFile, function(result) {
        var json = JSON.parse(result);
        for (var x = 0; x < json.length; x++) {
            $("#submit-vehicle-form").append(
                "<div class='form-group'>" +
                "<label>" + json[x] + "</label>" +
                "<input class='form-control' type='text' name='input-" +
                json[x] + "'></div>"
            );
        }
    });
});

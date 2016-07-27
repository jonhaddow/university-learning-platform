$(function() {
    $("#getMessageButton").click(function() {
        $.get("php/GetLatestMessage.php", function(result) {
            var lastMessage = JSON.parse(result);
            $("#response").text(lastMessage.messageContent);
        });
    });

    $("#sendMessageButton").click(function() {
        var message = $("#messageInput").val();
        $.post("php/PostMessage.php", {
            messageContent: message
        }, function(result) {
            alert(result);
        });
    });
});

$(document).ready(function () {
    let token = localStorage.getItem("token");

    if (!token) {
        window.location.href = "login.html";
        return;
    }

    loadProfile();

    function loadProfile() {
        $.ajax({
            url: "php/profile.php",
            type: "POST",
            dataType: "json",
            data: {
                action: "get",
                token: token
            },
            success: function (result) {
                if (result.status === "success" && result.profile) {
                    $("#age").val(result.profile.age);
                    $("#dob").val(result.profile.dob);
                    $("#contact").val(result.profile.contact);
                    $("#address").val(result.profile.address);
                } else if (result.status === "unauthorized") {
                    localStorage.clear();
                    window.location.href = "login.html";
                }
            }
        });
    }

    $("#saveBtn").click(function () {
        $.ajax({
            url: "php/profile.php",
            type: "POST",
            dataType: "json",
            data: {
                action: "save",
                token: token,
                age: $("#age").val(),
                dob: $("#dob").val(),
                contact: $("#contact").val(),
                address: $("#address").val()
            },
            success: function (result) {
                if (result.status === "success") {
                    $("#message").text(result.message).css("color", "green");
                } else {
                    $("#message").text(result.message).css("color", "red");
                }
            },
            error: function () {
                $("#message").text("Profile save failed").css("color", "red");
            }
        });
    });

    $("#logoutBtn").click(function () {
    $.ajax({
        url: "php/logout.php",
        type: "POST",
        dataType: "json",
        data: {
            token: token
        },
        success: function () {
            localStorage.clear();
            window.location.href = "login.html";
        }
    });
});
});
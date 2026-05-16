$(document).ready(function () {
    $("#loginBtn").click(function () {
        let email = $("#email").val();
        let password = $("#password").val();

        if (email === "" || password === "") {
            $("#message").text("Email and password required").css("color", "red");
            return;
        }

        $.ajax({
            url: "php/login.php",
            type: "POST",
            dataType: "json",
            data: {
                email: email,
                password: password
            },
            success: function (result) {
                if (result.status === "success") {
                    localStorage.setItem("token", result.token);
                    localStorage.setItem("email", result.email);

                    window.location.href = "profile.html";
                } else {
                    $("#message").text(result.message).css("color", "red");
                }
            },
            error: function () {
                $("#message").text("Login failed").css("color", "red");
            }
        });
    });
});
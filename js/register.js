$(document).ready(function () {
    $("#registerBtn").click(function () {
        let name = $("#name").val();
        let email = $("#email").val();
        let password = $("#password").val();

        if (name === "" || email === "" || password === "") {
            $("#message").text("All fields are required").css("color", "red");
            return;
        }

        $.ajax({
            url: "php/register.php",
            type: "POST",
            dataType: "json",
            data: {
                name: name,
                email: email,
                password: password
            },
            success: function (result) {
                if (result.status === "success") {
                    $("#message").text(result.message).css("color", "green");

                    setTimeout(function () {
                        window.location.href = "login.html";
                    }, 1000);
                } else {
                    $("#message").text(result.message).css("color", "red");
                }
            },
            error: function () {
                $("#message").text("Something went wrong").css("color", "red");
            }
        });
    });
});
var x = document.getElementById("login");
var y = document.getElementById("signup");
var z = document.getElementById("btn");

function signup() {
    x.style.left = "-500px";
    y.style.left = "100px";
    z.style.left = "110px";
}

function login() {
    x.style.left = "90px";
    y.style.left = "550px";
    z.style.left = "0";
}


// toogling the password to show or hide

function togglePassword() {
    var passwordField = document.getElementById("password");
    var showPassword = document.getElementById("showPassword");

    if (showPassword.checked) {
        passwordField.type = "text";
    } else {
        passwordField.type = "password";
    }
}

document.getElementById("showPassword").addEventListener("change", togglePassword);

// Redirect to Login
function updateFormAction() {
    var form = document.getElementById("signup");
    form.action = "login.php";

    var elements = form.elements;
    for (var i = 0; i < elements.length; i++) {
        elements[i].disabled = true;
    }

    form.submit();
}
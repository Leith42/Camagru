document.addEventListener("DOMContentLoaded", function () {
    "use strict";
    var submitButton = document.getElementsByName("submit-button")[0];

    submitButton.addEventListener("click", function (event) {
        var xmlhttp = new XMLHttpRequest();
        var form = document.forms["signup-form"];
        var toSend =
            'username=' + encodeURIComponent(form.elements.username.value) +
            '&email=' + encodeURIComponent(form.elements.email.value) +
            '&password=' + encodeURIComponent(form.elements.password.value) +
            '&passwordRepeat=' + encodeURIComponent(form.elements.passwordRepeat.value);

        xmlhttp.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                var response = JSON.parse(xmlhttp.response);

                if (response === 'FormIsValid') {
                    window.location.replace('/client/signup-success.php');
                }
                else {
                    printUsernameError(form, response);
                    printPasswordError(form, response);
                    printEmailError(form, response);
                }
            }
        };

        xmlhttp.open("POST", "/server/signup.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send(toSend);
        event.preventDefault();
    });
});

function printUsernameError(form, response) {
    "use strict";
    var prevError = document.getElementById("usernameError");
    if (prevError) {
        prevError.remove();
    }

    var usernameError = document.createElement('P');
    usernameError.id = "usernameError";
    if (response.usernameIsValid === false) {
        addText(usernameError, "• The username must be between 3 and 12 characters long.<br />");
        form.elements.username.style.borderColor = 'red';
        form.appendChild(usernameError);
    }
    else if (response.usernameIsAvailable === false) {
        addText(usernameError, "• This username is already taken.<br />");
        form.elements.username.style.borderColor = 'red';
        form.appendChild(usernameError);
    }
    else {
        form.elements.username.style.borderColor = 'green';
    }
}

function printPasswordError(form, response) {
    "use strict";
    var prevError = document.getElementById("passwordError");
    if (prevError) {
        prevError.remove();
    }

    var passwordError = document.createElement('P');
    passwordError.id = "passwordError";
    if (response.passwordIsValid === false) {
        addText(passwordError, "• The password must be greater than 6 characters and must contains: <br />" +
            "   * 1 or more special character.<br />" +
            "   * 1 or more digit.<br />" +
            "   * both uppercase and lowercase characters.<br />");
        form.elements.password.style.borderColor = 'red';
        form.appendChild(passwordError);
    }
    else if (response.passwordsAreMatching === false) {
        addText(passwordError, "• Password doesn't match.<br />");
        form.elements.password.style.borderColor = 'red';
        form.elements.passwordRepeat.style.borderColor = 'red';
        form.appendChild(passwordError);
    }
    else {
        form.elements.password.style.borderColor = 'green';
        form.elements.passwordRepeat.style.borderColor = 'green';
    }
}

function printEmailError(form, response) {
    "use strict";
    var prevError = document.getElementById("emailError");
    if (prevError) {
        prevError.remove();
    }

    var emailError = document.createElement('P');
    emailError.id = "emailError";
    if (response.emailIsValid === false) {
        addText(emailError, "• The email format is invalid.");
        form.elements.email.style.borderColor = 'red';
        form.appendChild(emailError);
    }
    else {
        form.elements.email.style.borderColor = 'green';
    }
}

function addText(node, text) {
    "use strict";
    var t = text.split(/\s*<br ?\/?>\s*/i), i;

    node.style.color = 'red';
    if (t[0].length > 0) {
        node.appendChild(document.createTextNode(t[0]));
    }
    for (i = 1; i < t.length; i++) {
        node.appendChild(document.createElement('BR'));
        if (t[i].length > 0) {
            node.appendChild(document.createTextNode(t[i]));
        }
    }
}

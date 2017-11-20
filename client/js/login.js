document.addEventListener("DOMContentLoaded", function () {
    "use strict";
    var submitButton = document.getElementsByName("submit-button")[0];

    submitButton.addEventListener("click", function (event) {
        var xmlhttp = new XMLHttpRequest();
        var form = document.forms["login-form"];
        var toSend =
            'username=' + encodeURIComponent(form.elements.username.value) +
            '&password=' + encodeURIComponent(form.elements.password.value);

        xmlhttp.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                var response = JSON.parse(xmlhttp.response);

                if (response === 'FormIsValid') {
                    window.location.replace('/');
                }
                else {
                    console.log(response);
                    printLoginError(form);
                }
            }
        };

        xmlhttp.open("POST", "/server/login.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send(toSend);
        event.preventDefault();
    });
});

function printLoginError(form) {
    "use strict";
    var prevError = document.getElementById("loginError");

    if (!prevError) {
        var loginError = document.createElement('P');
        loginError.id = "loginError";
        loginError.style.color = 'red';
        addText(loginError, "• Username and/or password invalid.");
        form.appendChild(loginError);
        form.elements.username.style.borderColor = 'red';
        form.elements.password.style.borderColor = 'red';
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

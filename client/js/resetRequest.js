document.addEventListener("DOMContentLoaded", function () {
    "use strict";
    var submitButton = document.getElementsByName("submit-button")[0];

    submitButton.addEventListener("click", function (event) {
        var xmlhttp = new XMLHttpRequest();
        var form = document.forms["reset-request-form"];
        var toSend = 'email=' + encodeURIComponent(form.elements.email.value);
        xmlhttp.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                var response = JSON.parse(xmlhttp.response);

                if (response === 'FormIsValid') {
                    window.location.replace('/client/reset-request-success.php');
                }
                else {
                    printEmailError(form);
                }
            }
        };

        xmlhttp.open("POST", "/server/reset-request-password.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send(toSend);
        event.preventDefault();
    });
});

function printEmailError(form) {
    "use strict";
    var prevError = document.getElementById("emailError");
    if (prevError) {
        prevError.remove();
    }

    var emailError = document.createElement('P');
    emailError.id = "emailError";
    addText(emailError, "• The email format is invalid.");
    form.elements.email.style.borderColor = 'red';
    form.appendChild(emailError);
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

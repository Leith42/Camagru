document.addEventListener("DOMContentLoaded", function () {
    "use strict";
    var submitButton = document.getElementsByName("submit-button")[0];

    submitButton.addEventListener("click", function (event) {
        var xmlhttp = new XMLHttpRequest();
        var form = document.forms["change-password-form"];
        var toSend =
            'password=' + encodeURIComponent(form.elements.password.value) +
            '&passwordConfirm=' + encodeURIComponent(form.elements.passwordConfirm.value);
        event.preventDefault();

        xmlhttp.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                var response = JSON.parse(xmlhttp.response);

                if (response === 'Success') {
                    window.location.replace('/client/personal.php');
                } else {
                    printFormatError(form);
                }
            }
        };

        xmlhttp.open("POST", "/server/change-password.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send(toSend);
    });
});

function printFormatError(form) {
    "use strict";
    var prevError = document.getElementById("formatError");
    if (!prevError) {
        var formatError = document.createElement('P');
        formatError.id = "formatError";
        addText(formatError, "• The password must be greater than 6 characters and must contains: <br />" +
            "   * 1 or more special character.<br />" +
            "   * 1 or more digit.<br />" +
            "   * both uppercase and lowercase characters.<br />");
        form.elements.password.style.borderColor = 'red';
        form.elements.passwordConfirm.style.borderColor = 'red';
        form.appendChild(formatError);
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

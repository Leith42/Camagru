document.addEventListener("DOMContentLoaded", function () {
    "use strict";
    var submitButton = document.getElementsByName("submit-button")[0];

    submitButton.addEventListener("click", function (event) {
        var xmlhttp = new XMLHttpRequest();
        var form = document.forms["change-username-form"];
        var toSend = 'username=' + encodeURIComponent(form.elements.username.value);
        event.preventDefault();

        xmlhttp.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                var response = JSON.parse(xmlhttp.response);

                if (response === 'Success') {
                    window.location.replace('/client/personal.php');
                } else if (response === 'NotValid') {
                    printFormatError(form);
                } else if (response === 'NotAvailable') {
                    printAlreadyTakenError(form);
                } else {
                    window.location.replace('/client/error.php');
                }
            }
        };

        xmlhttp.open("POST", "/server/change-username.php", true);
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
        addText(formatError, "• The username must be between 3 and 12 characters long.");
        form.elements.username.style.borderColor = 'red';
        form.appendChild(formatError);
    }
}

function printAlreadyTakenError(form) {
    "use strict";
    var prevError = document.getElementById("alreadyTakenError");
    if (!prevError) {
        var alreadyTakenError = document.createElement('P');
        alreadyTakenError.id = "alreadyTakenError";
        addText(alreadyTakenError, "• The username is already taken, sorry.");
        form.elements.username.style.borderColor = 'red';
        form.appendChild(alreadyTakenError);
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

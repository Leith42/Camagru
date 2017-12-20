document.addEventListener("DOMContentLoaded", function () {
    "use strict";
    var submitButton = document.getElementsByName("submit-button")[0];
    var deleteButton = document.getElementsByName("delete-button");

    for (var i = 0; i < deleteButton.length; i++) {
        deleteButton[i].addEventListener("click", function (event) {
            //TODO: Delete button.
        });
    }

    submitButton.addEventListener("click", function (event) {
        var xmlhttp = new XMLHttpRequest();
        var comment = document.getElementsByName("comment-input")[0].value;
        var toSend =
            'comment=' + encodeURIComponent(comment) +
            '&id=' + encodeURIComponent(getUrlVars().id);

        xmlhttp.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                var response = JSON.parse(xmlhttp.response);

                if (response === 'Failure') {
                    printCommentError();
                }
                window.location.reload();
            }
        };

        xmlhttp.open("POST", "/server/add-comment.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send(toSend);
    });


});

function printCommentError() {
    "use strict";
    var prevError = document.getElementById("commentError");

    if (!prevError) {
        var commentError = document.createElement('P');
        var submitButton = document.getElementsByName("submit-button")[0];
        var commentInput = document.getElementsByName("comment-input")[0];

        commentError.id = "commentError";
        addText(commentError, "<br />• An error occured, please be sure that your comment is not greater than 160 characters.<br />");
        commentInput.style.borderColor = 'red';
        submitButton.appendChild(commentError);
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

function getUrlVars() {
    "use strict";
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function (m, key, value) {
        vars[key] = value;
    });
    return vars;
}

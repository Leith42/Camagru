document.addEventListener("DOMContentLoaded", function () {
    "use strict";
    var submitButton = document.getElementsByName("submit-button")[0];
    var deleteCommentButton = document.getElementsByName("delete-comment-button");
    var deletePhotoButton = document.getElementById("delete-photo-button");
    var xmlhttp = new XMLHttpRequest();

    for (var i = 0; i < deleteCommentButton.length; i++) {
        deleteCommentButton[i].addEventListener("click", deleteComment);
    }

    function deleteComment(event) {
        var toSend = 'id=' + event.target.value;

        xmlhttp.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                var response = JSON.parse(xmlhttp.response);

                if (response === 'Success') {
                    window.location.reload();
                } else {
                    window.location.replace('/client/error.php');
                }
            }
        };

        xmlhttp.open("POST", "/server/delete-comment.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send(toSend);
    }

    if (submitButton) {
        submitButton.addEventListener("click", function (event) {
            var comment = document.getElementsByName("comment-input")[0].value;
            var toSend =
                'comment=' + encodeURIComponent(comment) +
                '&id=' + encodeURIComponent(getUrlVars().id);

            xmlhttp.onreadystatechange = function () {
                if (this.readyState === 4 && this.status === 200) {
                    var response = JSON.parse(xmlhttp.response);

                    if (response === 'Failure') {
                        printCommentError();
                    } else {
                        window.location.reload();
                    }
                }
            };

            xmlhttp.open("POST", "/server/add-comment.php", true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send(toSend);
        });
    }

    if (deletePhotoButton) {
        deletePhotoButton.addEventListener("click", function (event) {
            if (confirm("Are you sure you want to delete this photo?") === true) {
                var toSend = 'id=' + event.target.value;

                xmlhttp.onreadystatechange = function () {
                    if (this.readyState === 4 && this.status === 200) {
                        var response = JSON.parse(xmlhttp.response);

                        if (response === 'Success') {
                            window.location.replace('/client/gallery.php');
                        } else {
                            window.location.replace('/client/error.php');
                        }
                    }
                };

                xmlhttp.open("POST", "/server/delete-photo.php", true);
                xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xmlhttp.send(toSend);
            }
        });
    }
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

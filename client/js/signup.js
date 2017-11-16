document.addEventListener("DOMContentLoaded", function () {
    "use strict";
    var submitButton = document.getElementsByName("submit-button")[0];

    submitButton.addEventListener("click", function (event) {
        // event.preventDefault();
        var form = document.forms["signup-form"];
        var username = form.elements.username;
        var email = form.elements.email;
        var password = form.elements.password;
        var passwordRepeat = form.elements.passwordRepeat;

        // AJAX
        var xmlhttp = new XMLHttpRequest();

        xmlhttp.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                document.getElementById("button").innerHTML = xmlhttp.responseText;
            }
        };

        xmlhttp.open("GET", "/forms/api.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send();
        console.log('sent');
        event.preventDefault();
    });
});

// function validate() {
//     "use strict";
//     var form = document.forms["signup-form"];
//     var username = form.elements.username;
//     var email = form.elements.email;
//     var password = form.elements.password;
//     var passwordRepeat = form.elements.passwordRepeat;
//     var errorElem = document.createElement('P');
//     var currentErrors = document.getElementById("error");
//     var isValid = true;
//
//     errorElem.style.color = 'red';
//     errorElem.id = 'error';
//     if (currentErrors !== null) {
//         currentErrors.remove();
//     }
//     if (usernameChecker(form, username, errorElem) === false) {
//         isValid = false;
//     }
//     if (emailChecker(form, email, errorElem) === false) {
//         isValid = false;
//     }
//     if (passwordChecker(form, password, passwordRepeat, errorElem) === false) {
//         isValid = false;
//     }
//     return isValid;
// }
//
// function usernameChecker(form, username, errorElem) {
//     "use strict";
//     var stringRangeRegEx = /^[a-zA-Z0-9_]{3,12}$/;
//     var alphaNumericRegEx = /^[a-zA-Z0-9_]*$/;
//     var isValid = true;
//
//     if (!username.value.match(stringRangeRegEx)) {
//         addText(errorElem, "* The username must be between 3 and 12 characters long.<br />");
//         form.appendChild(errorElem);
//         username.style.borderColor = 'red';
//         isValid = false;
//     }
//     if (!username.value.match(alphaNumericRegEx)) {
//         addText(errorElem, "* The username should only contain alphanumeric characters.<br />");
//         form.appendChild(errorElem);
//         username.style.borderColor = 'red';
//         isValid = false;
//     }
//     if (isValid === true) {
//         username.style.borderColor = 'green';
//     }
//     return isValid;
// }
//
// function emailChecker(form, email, errorElem) {
//     "use strict";
//     if ((email.value.match(/@/g) || []).length !== 1) {
//         addText(errorElem, "* The email format is invalid.<br />");
//         form.appendChild(errorElem);
//         email.style.borderColor = 'red';
//         return false;
//     }
//     email.style.borderColor = 'green';
//     return true;
// }
//
// function passwordChecker(form, password, passwordRepeat, errorElem) {
//     "use strict";
//     var passwordSecurityRegEx = /((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W]).{6,20})/;
//     var isValid = true;
//
//     if ((password.value !== passwordRepeat.value)) {
//         addText(errorElem, "* Password doesn't match.<br />");
//         form.appendChild(errorElem);
//         isValid = false;
//     }
//     if (!password.value.match(passwordSecurityRegEx)) {
//         addText(errorElem,
//             "* The password must be between 6 and 20 characters long and should contain at least: <br />" +
//             "• 1 special character.<br />" +
//             "• 1 digit.<br />" +
//             "• both uppercase and lowercase characters.<br />");
//         form.appendChild(errorElem);
//         isValid = false;
//     }
//     if (isValid === false) {
//         password.style.borderColor = 'red';
//         passwordRepeat.style.borderColor = 'red';
//     }
//     else {
//         password.style.borderColor = 'green';
//         passwordRepeat.style.borderColor = 'green';
//     }
//     return isValid;
// }
//
// function addText(node, text) {
//     "use strict";
//     var t = text.split(/\s*<br ?\/?>\s*/i), i;
//
//     if (t[0].length > 0) {
//         node.appendChild(document.createTextNode(t[0]));
//     }
//     for (i = 1; i < t.length; i++) {
//         node.appendChild(document.createElement('BR'));
//         if (t[i].length > 0) {
//             node.appendChild(document.createTextNode(t[i]));
//         }
//     }
// }

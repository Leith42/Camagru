document.addEventListener("DOMContentLoaded", function () {
    "use strict";

    var currentSticker = null;
    var sticker1 = document.getElementById('sticker1');
    var sticker2 = document.getElementById('sticker2');
    var sticker3 = document.getElementById('sticker3');

    var retry = document.getElementById('retry');
    var upload = document.getElementById('upload');
    var canvas = document.getElementById('canvas');
    var photo = document.getElementById('photo');
    var form = document.getElementById('form-upload');
    var formSubmitButton = document.getElementById('form-submit');
    var fileSelect = document.getElementById('fileToUpload');

    retry.addEventListener('click', function (ev) {
        ev.preventDefault();
        clearPhoto();
        formSubmitButton.value = 'Upload Image';
    }, false);

    upload.addEventListener('click', function (ev) {
            var xmlhttp = new XMLHttpRequest();
            var toSend = 'image=' + encodeURIComponent(photo.getAttribute('src'));

            xmlhttp.onreadystatechange = function () {
                if (this.readyState === 4 && this.status === 200) {
                    var response = JSON.parse(xmlhttp.response);

                    if (response === 'Failure') {
                        window.location.replace('/client/error.php');
                    } else {
                        upload.style.display = 'none';
                        window.location.replace('/client/upload-success.php');
                    }
                }
            };

            xmlhttp.open("POST", "/server/upload.php", true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send(toSend);


        }, false
    );

    sticker1.addEventListener('click', function (ev) {
        if (currentSticker === sticker1.getAttribute("src")) {
            clearSticker();
            sticker1.classList.remove("sticker-active");
            formSubmitButton.style.backgroundColor = 'red';
        }
        else {
            currentSticker = sticker1.getAttribute("src");
            sticker1.className = "sticker-active";
            sticker2.classList.remove("sticker-active");
            sticker3.classList.remove("sticker-active");
            if (fileSelect.files[0]) {
                formSubmitButton.style.backgroundColor = 'green';
            }
        }
    }, false);

    sticker2.addEventListener('click', function (ev) {
        if (currentSticker === sticker2.getAttribute("src")) {
            clearSticker();
            sticker2.classList.remove("sticker-active");
            formSubmitButton.style.backgroundColor = 'red';
        }
        else {
            currentSticker = sticker2.getAttribute("src");
            sticker1.classList.remove("sticker-active");
            sticker2.className = "sticker-active";
            sticker3.classList.remove("sticker-active");
            if (fileSelect.files[0]) {
                formSubmitButton.style.backgroundColor = 'green';
            }
        }
    }, false);

    sticker3.addEventListener('click', function (ev) {
        if (currentSticker === sticker3.getAttribute("src")) {
            clearSticker();
            sticker3.classList.remove("sticker-active");
            formSubmitButton.style.backgroundColor = 'red';
        }
        else {
            currentSticker = sticker3.getAttribute("src");
            sticker1.classList.remove("sticker-active");
            sticker2.classList.remove("sticker-active");
            sticker3.className = "sticker-active";
            if (fileSelect.files[0]) {
                formSubmitButton.style.backgroundColor = 'green';
            }
        }
    }, false);

    fileSelect.addEventListener('change', function (ev) {
        if (currentSticker) {
            formSubmitButton.style.backgroundColor = 'green';
        }
    }, false);

    form.onsubmit = function (ev) {
        ev.preventDefault();
        var file = fileSelect.files[0];

        if (!file) {
            printNoFileError();
        }
        else if (currentSticker === null) {
            printStickerError();
        }
        else if (!file.type.match('image.jpeg') && !file.type.match('image.png') && !file.type.match('image.gif')) {
            printBadFormat();
        }
        else if (file.size > 2000000) {
            printBadSize();
        }
        else {
            var formData = new FormData();
            var xhr = new XMLHttpRequest();

            formData.append('image', file, file.name);
            formData.append('sticker', currentSticker);

            xhr.onreadystatechange = function () {
                if (this.readyState === 4 && this.status === 200) {
                    formSubmitButton.value = 'Uploading...';
                    var response = JSON.parse(xhr.response);

                    console.log(response);
                    if (response === 'Failure') {
                        window.location.replace('/client/error.php');
                    }
                    else {
                        photo.setAttribute('src', response);
                        document.getElementsByClassName("photo")[0].style.display = "inline-block";
                        document.getElementsByClassName("webcam")[0].style.display = "none";
                    }
                }
            };

            xhr.open("POST", "/server/montage.php", true);
            xhr.send(formData);
        }
    };

    function clearPhoto() {
        var context = canvas.getContext('2d');
        context.fillStyle = "#AAA";
        context.fillRect(0, 0, canvas.width, canvas.height);

        var data = canvas.toDataURL('image/png');
        photo.setAttribute('src', data);
        document.getElementsByClassName("photo")[0].style.display = "none";
        document.getElementsByClassName("webcam")[0].style.display = "inline-block";
        clearSticker();
        clearErrors();
    }

    function clearSticker() {
        currentSticker = null;
        formSubmitButton.style.backgroundColor = 'red';
    }

    function clearErrors() {
        var noFileError = document.getElementById("noFileError");
        var stickerError = document.getElementById("stickerError");
        var formatError = document.getElementById("badFormatError");

        if (noFileError) {
            noFileError.remove();
        }
        if (stickerError) {
            stickerError.remove();
        }
        if (formatError) {
            formatError.remove();
        }
        sticker1.classList.remove("sticker-active");
        sticker2.classList.remove("sticker-active");
        sticker3.classList.remove("sticker-active");
    }

    function printNoFileError() {
        var prevError = document.getElementById("noFileError");
        if (!prevError) {
            var noFileError = document.createElement('P');
            noFileError.id = "noFileError";
            addText(noFileError, "• Please select an image to upload.");
            form.appendChild(noFileError);
        }
    }

    function printStickerError() {
        var prevError = document.getElementById("stickerError");
        if (!prevError) {
            var stickerError = document.createElement('P');
            stickerError.id = "stickerError";
            addText(stickerError, "• Please select a sticker.");
            form.appendChild(stickerError);
        }
    }

    function printBadFormat() {
        var prevError = document.getElementById("badFormatError");
        if (!prevError) {
            var badFormatError = document.createElement('P');
            badFormatError.id = "badFormatError";
            addText(badFormatError, "• The file selected is not an image (JPEG, PNG, GIF).");
            form.appendChild(badFormatError);
        }
    }

    function printBadSize() {
        var prevError = document.getElementById("badSizeError");
        if (!prevError) {
            var badSizeError = document.createElement('P');
            badSizeError.id = "badSizeError";
            addText(badSizeError, "• The file selected is too big, limit is 2mb.");
            form.appendChild(badSizeError);
        }
    }

});

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

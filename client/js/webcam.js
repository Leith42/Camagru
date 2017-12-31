(function () {
    "use strict";
    // The width and height of the captured photo. We will set the
    // width to the value defined here, but the height will be
    // calculated based on the aspect ratio of the input stream.

    var width = 720;    // We will scale the photo width to this
    var height = 0;     // This will be computed based on the input stream

    // |streaming| indicates whether or not we're currently streaming
    // video from the camera. Obviously, we start at false.

    var streaming = false;

    // The various HTML elements we need to configure or control. These
    // will be set by the startup() function.

    var video = null;
    var canvas = null;
    var photo = null;
    var shot = null;
    var retry = null;
    var upload = null;

    var currentSticker = null;
    var sticker1 = null;
    var sticker2 = null;
    var sticker3 = null;
    var sticker4 = null;
    var webcamElement = null;
    var stickerElement = null;

    function startup() {
        video = document.getElementById('video');
        canvas = document.getElementById('canvas');
        photo = document.getElementById('photo');
        shot = document.getElementById('shot');
        retry = document.getElementById('retry');
        upload = document.getElementById('upload');
        sticker1 = document.getElementById('sticker1');
        sticker2 = document.getElementById('sticker2');
        sticker3 = document.getElementById('sticker3');
        sticker4 = document.getElementById('sticker4');
        webcamElement = document.getElementsByClassName('webcam');
        stickerElement = document.createElement('img');
        stickerElement.id = 'overlay';

        // Older browsers might not implement mediaDevices at all, so we set an empty object first
        if (navigator.mediaDevices === undefined) {
            navigator.mediaDevices = {};
        }

        // Some browsers partially implement mediaDevices. We can't just assign an object
        // with getUserMedia as it would overwrite existing properties.
        // Here, we will just add the getUserMedia property if it's missing.
        if (navigator.mediaDevices.getUserMedia === undefined) {
            navigator.mediaDevices.getUserMedia = function (constraints) {

                // First get ahold of the legacy getUserMedia, if present
                var getUserMedia = navigator.webkitGetUserMedia || navigator.mozGetUserMedia;

                // Some browsers just don't implement it - return a rejected promise with an error
                // to keep a consistent interface
                if (!getUserMedia) {
                    return Promise.reject(new Error('getUserMedia is not implemented in this browser'));
                }

                // Otherwise, wrap the call to the old navigator.getUserMedia with a Promise
                return new Promise(function (resolve, reject) {
                    getUserMedia.call(navigator, constraints, resolve, reject);
                });
            }
        }

        navigator.mediaDevices.getUserMedia({audio: false, video: true})
            .then(function (stream) {
                var video = document.querySelector('video');
                // Older browsers may not have srcObject
                if ("srcObject" in video) {
                    video.srcObject = stream;
                } else {
                    // Avoid using this in new browsers, as it is going away.
                    video.src = window.URL.createObjectURL(stream);
                }
                video.onloadedmetadata = function (e) {
                    video.play();
                };
            })
            .catch(function (err) {
                console.log(err.name + ": " + err.message);
            });

        video.addEventListener('canplay', function (ev) {
            if (!streaming) {
                height = video.videoHeight / (video.videoWidth / width);

                // Firefox currently has a bug where the height can't be read from
                // the video, so we will make assumptions if this happens.

                if (isNaN(height)) {
                    height = width / (4 / 3);
                }

                video.setAttribute('width', width);
                video.setAttribute('height', height);
                canvas.setAttribute('width', width);
                canvas.setAttribute('height', height);
                streaming = true;
            }
        }, false);

        shot.addEventListener('click', function (ev) {
            takepicture();
            ev.preventDefault();
        }, false);

        retry.addEventListener('click', function (ev) {
            clearPhoto();
            ev.preventDefault();
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
        }, false);

        sticker1.addEventListener('click', function (ev) {
            if (currentSticker === sticker1.getAttribute("src")) {
                clearSticker();
                shot.style.backgroundColor = 'red';
            }
            else {
                currentSticker = sticker1.getAttribute("src");
                stickerElement.src = '/client/img/sticker1.png';
                webcamElement[0].insertBefore(stickerElement, webcamElement[0].firstChild);
                shot.style.backgroundColor = 'green';
            }
        }, false);

        sticker2.addEventListener('click', function (ev) {
            if (currentSticker === sticker2.getAttribute("src")) {
                clearSticker();
                shot.style.backgroundColor = 'red';
            }
            else {
                currentSticker = sticker2.getAttribute("src");
                stickerElement.src = '/client/img/sticker2.png';
                webcamElement[0].insertBefore(stickerElement, webcamElement[0].firstChild);
                shot.style.backgroundColor = 'green';
            }
        }, false);

        sticker3.addEventListener('click', function (ev) {
            if (currentSticker === sticker3.getAttribute("src")) {
                clearSticker();
                shot.style.backgroundColor = 'red';
            }
            else {
                currentSticker = sticker3.getAttribute("src");
                stickerElement.src = '/client/img/sticker3.png';
                webcamElement[0].insertBefore(stickerElement, webcamElement[0].firstChild);
                shot.style.backgroundColor = 'green';
            }
        }, false);

        sticker4.addEventListener('click', function (ev) {
            if (currentSticker === sticker4.getAttribute("src")) {
                clearSticker();
                shot.style.backgroundColor = 'red';
            }
            else {
                currentSticker = sticker4.getAttribute("src");
                stickerElement.src = '/client/img/sticker4.png';
                webcamElement[0].insertBefore(stickerElement, webcamElement[0].firstChild);
                shot.style.backgroundColor = 'green';
            }
        }, false);
    }

    // Fill the photo with an indication that none has been
    // captured.

    function clearPhoto() {
        var context = canvas.getContext('2d');
        context.fillStyle = "#AAA";
        context.fillRect(0, 0, canvas.width, canvas.height);

        var data = canvas.toDataURL('image/png');
        photo.setAttribute('src', data);
        document.getElementsByClassName("photo")[0].style.display = "none";
        document.getElementsByClassName("webcam")[0].style.display = "inline-block";
        clearSticker();
    }

    function clearSticker() {
        currentSticker = document.getElementById('overlay');
        shot.style.backgroundColor = 'red';
        if (currentSticker) {
            currentSticker.remove();
        }
    }

    // Capture a photo by fetching the current contents of the video
    // and drawing it into a canvas, then converting that to a PNG
    // format data URL. By drawing it on an offscreen canvas and then
    // drawing that to the screen, we can change its size and/or apply
    // other changes before drawing it.

    function takepicture() {
        currentSticker = document.getElementById('overlay');
        if (currentSticker) {
            var xmlhttp = new XMLHttpRequest();
            var context = canvas.getContext('2d');

            if (width && height) {
                canvas.width = width;
                canvas.height = height;
                context.drawImage(video, 0, 0, width, height);
                var toSend =
                    'image=' + encodeURIComponent(canvas.toDataURL('image/png')) +
                    '&sticker=' + encodeURIComponent(currentSticker.getAttribute('src'));

                xmlhttp.onreadystatechange = function () {
                    if (this.readyState === 4 && this.status === 200) {
                        var response = JSON.parse(xmlhttp.response);
                        if (response !== "Failure") {
                            photo.setAttribute('src', response);
                            document.getElementsByClassName("photo")[0].style.display = "inline-block";
                            document.getElementsByClassName("webcam")[0].style.display = "none";
                        }
                    }
                };

                xmlhttp.open("POST", "/server/montage.php", true);
                xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xmlhttp.send(toSend);
            } else {
                clearPhoto();
            }
        }
    }

    // Set up our event listener to run the startup process
    // once loading is complete.
    window.addEventListener('load', startup, false);
})();
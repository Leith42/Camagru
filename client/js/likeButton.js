document.addEventListener("DOMContentLoaded", function () {
    "use strict";

    var likeButton = document.getElementById('like-button');
    var likeCounter = document.getElementById('like-counter');
    var xmlhttp = new XMLHttpRequest();

    likeButton.addEventListener("click", function (event) {
        var toSend = 'photo_id=' + encodeURIComponent(likeButton.value);

        xmlhttp.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                var response = JSON.parse(xmlhttp.response);

                if (response === 'active') {
                    likeButton.src = "/client/img/like-black.png";
                    likeCounter.innerHTML--;
                } else {
                    likeButton.src = "/client/img/like-red.png";
                    likeCounter.innerHTML++;
                }
            }
        };

        xmlhttp.open("POST", "/server/like.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send(toSend);
    });
});
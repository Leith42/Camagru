<?php
session_start();

if (!isset($_SESSION['user'])) {
	header('Location: /client/forms/login-form.php');
	exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<?php include "partials/head.html"; ?>
<body>
<?php include "partials/header.php"; ?>
<script src="/client/js/webcam.js" type="text/javascript"></script>
<script src="/client/js/selectImage.js" type="text/javascript"></script>
<main class="center">
	<div class="image-box">
		<div class="image"><img src="/client/img/lights.png" onclick=hello();></div>
		<div class="image"><img src="/client/img/flame.png" onclick=hello();></div>
		<div class="image"><img src="/client/img/storm.png" onclick=hello();></div>
	</div>
	<div class="webcam">
		<img class="overlay" src="/client/img/flame.png">
		<video id="video">Video stream not available.</video>
		<button class="webcam-button" id="shot">Take photo</button>
	</div>
	<canvas id="canvas"></canvas>
	<div class="photo">
		<img id="photo" alt="The screen capture will appear in this box.">
		<button class="webcam-button" id="retry">Retry</button>
		<button class="webcam-button" id="upload">Upload</button>
	</div>
</main>
<?php include "partials/footer.html"; ?>
</body>
</html>
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
<!--<script src="/client/js/webcam.js" type="text/javascript"></script>-->
<script src="/client/js/upload.js" type="text/javascript"></script>
<main class="center">
	<div class="image-box">
		<div class="image"><img id="sticker1" src="/client/img/lights.png"></div>
		<div class="image"><img id="sticker2" src="/client/img/flame.png"></div>
		<div class="image"><img id="sticker3" src="/client/img/storm.png"></div>
	</div>
	<div class="webcam">
		<form id="form-upload" method="post" enctype="multipart/form-data">
			Select a sticker and image to upload:
			<input type="file" name="fileToUpload" id="fileToUpload">
			<input id="form-submit" type="submit" value="Upload Image" name="submit">
		</form>
		<!--		<button class="webcam-button red-background" id="shot">Take photo</button>-->
	</div>
	<canvas id="canvas"></canvas>
	<div class="photo">
		<img src="" id="photo" alt="The screen capture will appear in this box.">
		<button class="webcam-button green-background" id="retry">Retry</button>
		<button class="webcam-button green-background" id="upload">Upload</button>
	</div>
</main>
<?php include "partials/footer.html"; ?>
</body>
</html>

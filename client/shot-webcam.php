<?php
session_start();

if (!isset($_SESSION['user'])) {
	header('Location: /client/forms/login-form.php');
	exit();
}

require_once('../autoload.php');

use server\classes\Database;
use server\classes\GalleryManager;

try {
	$db = Database::getMysqlConnection();
} catch (PDOException $e) {
	exit($e->getMessage());
}

$galleryManager = new GalleryManager($db);
?>
<!DOCTYPE html>
<html lang="en">
<?php include "partials/head.html"; ?>
<body>
<?php include "partials/header.php"; ?>
<script src="/client/js/webcam.js" type="text/javascript"></script>
<main class="center">
	<div class="sticker-box">
		<div class="image" id="sticker">
			<img id="sticker1" src="/client/img/sticker1.png">
		</div>
		<div class="image" id="sticker">
			<img id="sticker2" src="/client/img/sticker2.png">
		</div>
		<div class="image" id="sticker">
			<img id="sticker3" src="/client/img/sticker3.png">
		</div>
		<div class="image" id="sticker">
			<img id="sticker4" src="/client/img/sticker4.png">
		</div>
	</div>
	<div class="webcam">
		<video id="video">Video stream not available.</video>
		<button id="shot" class="webcam-button red-background">Take photo</button>
	</div>
	<canvas id="canvas"></canvas>
	<div class="photo">
		<img src="" id="photo" alt="The screen capture will appear in this box.">
		<button class="webcam-button green-background" id="retry">Retry</button>
		<button class="webcam-button green-background" id="upload">Upload</button>
	</div>
	<div class="wrapper-last-photos">
		<?php $galleryManager->printLastPhotos($_SESSION['user']); ?>
	</div>
</main>
<?php include "partials/footer.html"; ?>
</body>
</html>
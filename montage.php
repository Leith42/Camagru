<?php
require_once('autoload.php');
session_start();

if (!isset($_SESSION['user'])) {
	header('Location: ' . 'forms/login.php');
	exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include "partials/head.html"; ?>
<body>
<?php include "partials/header.php"; ?>
<script src="scripts/webcam.js" type="text/javascript"></script>
<main class="center">
	<div class="webcam">
		<video id="video"></video>
		<button id="shot">Shot!</button>
	</div>
<!--	<canvas id="canvas"></canvas>-->
<!--	<div class="webcam">-->
<!--		<img id="photo" alt="The screen capture will appear in this box.">-->
<!--	</div>-->

</main>
<?php include "partials/footer.html"; ?>
</body>
</html>
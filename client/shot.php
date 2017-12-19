<?php
session_start();

if (!isset($_SESSION['user'])) {
	header('Location: /client/forms/login-form.php');
	exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<?php include "../client/partials/head.html"; ?>
<body class="center">
<?php include "../client/partials/header.php"; ?>
<main>
	<div id="shot-method">
		<a href="/client/shot-file.php">
			<figure>
				<img class="shot-method-image" src="/client/img/download.png">
				<figcaption>Upload image</figcaption>
			</figure>
		</a>
		<a href="/client/shot-webcam.php">
			<figure>
				<img class="shot-method-image" src="/client/img/webcam.png">
				<figcaption>Use your webcam</figcaption>
			</figure>
		</a>
	</div>
</main>
<?php include "../client/partials/footer.html"; ?>
</body>
</html>
<?php
session_start();
require_once('../../autoload.php');

if (isset($_SESSION['user']) || !isset($_GET['id'])) {
	header('Location: ' . '/');
	exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<?php include "../partials/head.html"; ?>
<body>
<?php include "../partials/header.php"; ?>
<script src="/client/js/reset.js" type="text/javascript"></script>
<main>
	<div class="form">
		<h3>Reset Password</h3>
		<p class="splash"></p>
		<form name="reset-form" method="post" action="/server/reset-password.php">
			<label>New Password</label>
			<input type="password" placeholder="Enter Password" name="password" required>

			<label>Repeat Password</label>
			<input type="password" placeholder="Repeat Password" name="passwordRepeat" required>

			<button type="submit" class="button" name="submit-button">Let's go!</button>
		</form>
	</div>
</main>
<?php include "../partials/footer.html"; ?>
</body>
</html>

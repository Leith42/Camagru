<?php
session_start();

if (!isset($_SESSION['user'])) {
	header('Location: ' . '/');
	exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include "../partials/head.html"; ?>
<body>
<?php include "../partials/header.php"; ?>
<script src="/client/js/changePassword.js" type="text/javascript"></script>
<main>
	<div class="form">
		<h3>Change password</h3>
		<p class="splash">
			We can help you change your password.<br/>
			Please choose a new one.<br/>
		</p>
		<form method="post" name="change-password-form">
			<label>Password</label>
			<input type="password" placeholder="Password" name="password" required>

			<label>Confirm password</label>
			<input type="password" placeholder="Password" name="passwordConfirm" required>

			<button name="submit-button" type="submit" class="button">Let's go!</button>
		</form>
	</div>
</main>
<?php include "../partials/footer.html"; ?>
</body>
</html>

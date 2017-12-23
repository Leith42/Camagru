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
<script src="/client/js/changeEmail.js" type="text/javascript"></script>
<main>
	<div class="form">
		<h3>Change email</h3>
		<p class="splash">
			We can help you change your email.<br/>
			Please choose a new one.<br/>
		</p>
		<form method="post" name="change-email-form">
			<label>New email</label>
			<input type="text" placeholder="Enter new email" name="email" required>
			<button name="submit-button" type="submit" class="button">Let's go!</button>
		</form>
	</div>
</main>
<?php include "../partials/footer.html"; ?>
</body>
</html>

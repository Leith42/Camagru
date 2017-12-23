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
<script src="/client/js/changeUsername.js" type="text/javascript"></script>
<main>
	<div class="form">
		<h3>Change username</h3>
		<p class="splash">
			We can help you change your username.<br/>
			Please choose a new one.<br/>
		</p>
		<form method="post" name="change-username-form">
			<label>New username</label>
			<input type="text" placeholder="Enter new username" name="username" required>
			<button name="submit-button" type="submit" class="button">Let's go!</button>
		</form>
	</div>
</main>
<?php include "../partials/footer.html"; ?>
</body>
</html>

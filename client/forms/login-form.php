<?php
session_start();

if (isset($_SESSION['user'])) {
	header('Location: ' . '/');
	exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<?php include "../partials/head.html"; ?>
<body>
<?php include "../partials/header.php"; ?>
<script src="/client/js/login.js" type="text/javascript"></script>
<main>
	<div class="form">
		<h3>Sign in</h3>
		<br/>
		<form name="login-form" method="post">
			<label>Username</label>
			<input type="text" placeholder="Enter Username" name="username" required>

			<label>Password</label>
			<input type="password" placeholder="Enter Password" name="password" required>

			<button name="submit-button" type="submit" class="button">Let's go!</button>
			<div style="text-align: right">
				<a id="forgot-password" href="reset-request-form.php">forgot password?</a>
			</div>
		</form>
	</div>
</main>
<?php include "../partials/footer.html"; ?>
</body>
</html>

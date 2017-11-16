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
<script src="../js/signup.js" type="text/javascript"></script>
<main>
	<div class="form">
		<h3>Sign up</h3>
		<p class="splash">
			We're glad to have you join us!<br/>
			We just need a couple of information about you before delivering the full experience.
		</p>

		<form name="signup-form" method="post" action="signup-form.php">
			<label>Username</label>
			<input type="text" placeholder="Enter Username" name="username" required>

			<label>E-mail</label>
			<input type="text" placeholder="Enter Email" name="email" required>

			<label>Password</label>
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

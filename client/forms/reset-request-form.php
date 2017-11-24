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
<script src="/client/js/resetRequest.js" type="text/javascript"></script>
<main>
	<div class="form">
		<h3>Password Reset</h3>
		<p class="splash">
			We can help you reset your password.<br/>
			First, enter your email and follow the instructions we will send you.<br/>
		</p>
		<form method="post" name="reset-request-form">
			<label>Email</label>
			<input type="text" placeholder="Enter Email" name="email" required>

			<button name="submit-button" type="submit" class="button">Let's go!</button>
		</form>
	</div>
</main>
<?php include "../partials/footer.html"; ?>
</body>
</html>

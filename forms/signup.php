<?php
require_once('../autoload.php');
session_start();

use classes\Database;
use classes\UserManager;
use classes\Users;
use classes\EmailManager;
use classes\TokenManager;

if (isset($_SESSION['user'])) {
	header('Location: ' . '/');
	exit();
}

if (isset($_POST['username']) && isset($_POST['email']) &&
	isset($_POST['password']) && isset($_POST['passwordRepeat'])) {

	try {
		$db = Database::getMysqlConnection();
	} catch (PDOException $e) {
		exit('Failed to connect to the database.');
	}

	$userManager = new UserManager($db);
	$user = new Users([
		'username' => $_POST['username'],
		'password' => $_POST['password'],
		'email' => $_POST['email']
	]);

	if ($userManager->addUser($user) === true) {
		$emailManager = new EmailManager($db);
		$tokenManager = new TokenManager($db);

		$token = $tokenManager->createVerificationToken($user);
		$emailManager->sendVerificationEmail($user, $token);

		header('Location: ' . '/signup-success.php');
	}
	else {
		header('Location: ' . '/error.php');
	}
}

?>
<!DOCTYPE html>
<html lang="en">
<?php include "../partials/head.html"; ?>
<body>
<?php include "../partials/header.php"; ?>
<script src="signup.js" type="text/javascript"></script>
<main>
	<div class="form">
		<h3>Sign up</h3>
		<p class="splash">
			We're glad to have you join us!<br/>
			We just need a couple of information about you before delivering the full experience.
		</p>

		<form name="signup-form" method="post" action="signup.php" onsubmit="return validate();">
			<label>Username</label>
			<input type="text" placeholder="Enter Username" name="username" required>

			<label>E-mail</label>
			<input type="text" placeholder="Enter Email" name="email" required>

			<label>Password</label>
			<input type="password" placeholder="Enter Password" name="password" required>

			<label>Repeat Password</label>
			<input type="password" placeholder="Repeat Password" name="passwordRepeat" required>

			<button type="submit" class="button">Let's go!</button>
		</form>
	</div>
</main>
<?php include "../partials/footer.html"; ?>
</body>
</html>

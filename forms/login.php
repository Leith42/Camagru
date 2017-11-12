<?php
require_once('../autoload.php');
session_start();

use classes\UserManager;
use classes\Users;
use classes\Database;

if (isset($_SESSION['user'])) {
	header('Location: ' . '/');
	exit();
}
if (isset($_POST['username']) && isset($_POST['password'])) {

	$username = $_POST['username'];
	$password = $_POST['password'];

	try {
		$db = Database::getMysqlConnection();
	} catch (PDOException $e) {
		exit($e->getMessage());
	}

	$userManager = new UserManager($db);
	$user = $userManager->getUserByUserName($username);
	if (password_verify($password, $user['password']) === true) {
		$_SESSION['user'] = $username;
		header('Location: ' . '/');
	}
}

?>
<!DOCTYPE html>
<html lang="en">
<?php include "../partials/head.html"; ?>
<body>
<?php include "../partials/header.php"; ?>
<!--<script src="signup.js" type="text/javascript"></script>-->
<main>
	<div class="form">
		<h3>Sign in</h3>
		<br/>
		<form name="login-form" method="post" action="login.php">
			<label>Username</label>
			<input type="text" placeholder="Enter Username" name="username" required>

			<label>Password</label>
			<input type="password" placeholder="Enter Password" name="password" required>

			<button type="submit" class="button">Let's go!</button>
		</form>
	</div>
</main>
<?php include "../partials/footer.html"; ?>
</body>
</html>

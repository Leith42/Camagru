<?php
session_start();

if (!isset($_SESSION['user'])) {
	header('Location: error.php');
	exit();
}

require_once('../autoload.php');

use server\classes\UserManager;
use server\classes\Database;

try {
	$db = Database::getMysqlConnection();
} catch (PDOException $e) {
	die($e->getMessage());
}

$userManager = new UserManager($db);
$user = $userManager->getUserByUserName($_SESSION['user']);
?>
<!DOCTYPE html>
<html lang="en">
<?php include "partials/head.html"; ?>
<body class="center">
<?php include "partials/header.php"; ?>
<main class="center">
	<div class="form" id="list">
		<div>Hello <?php echo $_SESSION['user'] ?>!</div>
		<br/>
		<ul>
			<li><a href="my-gallery.php">My photos</a></li>
			<li><a href="forms/change-email.php">Change email</a></li>
			<li><a href="forms/change-username.php">Change username</a></li>
			<li><a href="forms/change-password.php">Change password</a></li>
			<?php
			if ($user->getEmailNotification() === 'yes') {
				echo '<li><a style="color: red" href="/server/email-notification.php?option=disable">Disable email notifications</a></li>';
			} else {
				echo '<li><a style="color: green" href="/server/email-notification.php?option=enable">Enable email notifications</a></li>';
			}
			?>
		</ul>
	</div>
</main>
<?php include "partials/footer.html"; ?>
</body>
</html>
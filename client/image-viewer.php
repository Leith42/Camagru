<?php
if (!isset($_GET['id'])) {
	header('Location: /client/error.php');
	exit();
}

session_start();

require_once('../autoload.php');

use server\classes\Database;
use server\classes\GalleryManager;
use server\classes\UserManager;
use server\classes\CommentManager;

try {
	$db = Database::getMysqlConnection();
} catch (PDOException $e) {
	die($e->getMessage());
}

$galleryManager = new GalleryManager($db);
$commentManager = new CommentManager($db);
$userManager = new UserManager($db);
$photo_id = $_GET['id'];

if (isset($_SESSION['user'])) {
	$user = $userManager->getUserByUserName($_SESSION['user']);
}
?>
<!DOCTYPE html>
<html lang="en">
<?php include "partials/head.html"; ?>
<body style="background: url('/client/img/bg_image.jpg'">
<?php include "partials/header.php"; ?>
<script src="/client/js/comments.js" type="text/javascript"></script>
<main class="center">
	<div id="wrapper-image-viewer">
		<?php $galleryManager->printPhoto($photo_id); ?>
	</div>
	<div id="wrapper-comment-block">
		<?php $commentManager->printComments($photo_id); ?>
	</div>
</main>
<?php include "partials/footer.html"; ?>
</body>
</html>

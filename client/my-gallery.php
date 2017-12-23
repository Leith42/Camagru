<?php
session_start();

require_once('../autoload.php');

use server\classes\Database;
use server\classes\GalleryManager;

try {
	$db = Database::getMysqlConnection();
} catch (PDOException $e) {
	die($e->getMessage());
}

$galleryManager = new GalleryManager($db);
?>
<!DOCTYPE html>
<html lang="en">
<?php include "partials/head.html"; ?>
<body style="background: url('/client/img/bg_gallery.jpg'">
<?php include "partials/header.php"; ?>
<main>
	<?php
	if (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0) {
		$page = $_GET['page'];
	} else {
		$page = 1;
	}

	$per_page = 8;
	$galleryManager->printPersonalGallery($page, $per_page, $_SESSION['user']);
	?>
</main>
<?php include "partials/footer.html"; ?>
</body>
</html>

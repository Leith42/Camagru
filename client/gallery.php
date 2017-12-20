<?php
session_start();

require_once('../autoload.php');

use server\classes\Database;
use server\classes\GalleryManager;
use server\classes\UserManager;

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
<!--<body style="background: url('http://www.hdesktops.com/wp-content/uploads/2013/09/Line-Grey-Wallpaper-80.jpg'">-->
<body>
<?php include "partials/header.php"; ?>
<main>
	<?php
	if (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0) {
		$page = $_GET['page'];
	} else {
		$page = 1;
	}

	$per_page = 8;
	$galleryManager->printGallery($page, $per_page);
	?>
</main>
<?php include "partials/footer.html"; ?>
</body>
</html>

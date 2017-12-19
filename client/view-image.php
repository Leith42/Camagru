<?php
if (!isset($_GET['id'])) {
	header('Location: /client/forms/login-form.php');
	exit();
}

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
<body>
<?php include "partials/header.php"; ?>
<main>
	<?php
		echo 'ready to work!';
	?>
</main>
<?php include "partials/footer.html"; ?>
</body>
</html>

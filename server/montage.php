<?php
session_start();

if (!isset($_SESSION['user'])) {
	header('Location: ' . '/');
	exit();
}

require_once('../autoload.php');

use server\classes\MontageManager;

if (isset($_POST['image']) && isset($_POST['sticker'])) {
	$img = str_replace('data:image/png;base64,', '', $_POST['image']);
	$img = str_replace(' ', '+', $img);
	$img = base64_decode($img);
	$img = imagecreatefromstring($img);
	$sticker = imagecreatefrompng('..' . $_POST['sticker']);

	if ($img && $sticker) {
		imagecopy($img, $sticker, 0, 0, 0, 0, imagesx($sticker), imagesy($sticker));

		ob_start();
		header('Content-Type: image/png');
		imagepng($img);
		$image_string = ob_get_contents();
		ob_end_clean();

		imagedestroy($img);
		die(json_encode("data:image/png;base64," . base64_encode($image_string)));
	}
} else if (isset($_FILES['image']) && isset($_POST['sticker'])) {
	$sizeMax = 2000000;

	if ($_FILES['image']['error'] === 0 && $_FILES['image']['size'] < $sizeMax) {
		$finfo = new finfo(FILEINFO_MIME_TYPE);
		$mimetype = $finfo->buffer(file_get_contents($_FILES['image']['tmp_name']));
		$imageTypeIsValid = in_array($mimetype, array('image/jpeg', 'image/png', 'image/gif'));

		if ($imageTypeIsValid) {
			$montage = new MontageManager();

			try {
				$img = $montage->createMontageFromFile($_FILES['image']['tmp_name'], $_POST['sticker'], 480, 360);
				$image_string = $montage->getStringFromImage($img, $mimetype);
			} catch (Exception $e) {
				die($e->getMessage());
			}

			die(json_encode($image_string));
		}
	}
}
die(json_encode('Failure'));

//TODO: Fix warnings, clean up this shit, do some tests.
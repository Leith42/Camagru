<?php
session_start();

if (!isset($_SESSION['user'])) {
	header('Location: ' . '/');
	exit();
}

require_once('../autoload.php');

if (isset($_POST['image']) && isset($_POST['sticker'])) {
	$img = str_replace('data:image/png;base64,', '', $_POST['image']);
	$img = str_replace(' ', '+', $img);
	$img = base64_decode($img);
	$img = imagecreatefromstring($img);
	$sticker = $_POST['sticker'];
	$sticker = imagecreatefrompng('..' . $sticker);

	if ($img && $sticker) {
		//	imagecopy($dest, $image, imagesx($dest)-imagesx($image), imagesy($dest)-imagesy($image), 0, 0, imagesx($image), imagesy($image));
		imagecopy($img, $sticker, 0, 0, 0, 0, imagesx($sticker), imagesy($sticker));

		ob_start();
		header('Content-Type: image/png');
		imagepng($img);
		$image_string = ob_get_contents();
		ob_end_clean();

		imagedestroy($img);
		die(json_encode("data:image/png;base64," . base64_encode($image_string)));
	}
}
die(json_encode('Failure'));

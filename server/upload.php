<?php
session_start();

if (!isset($_SESSION['user'])) {
	header('Location: ' . '/');
	exit();
}

require_once('../autoload.php');

use server\classes\GalleryManager;
use server\classes\Database;
use server\classes\UserManager;

if (isset($_POST['image'])) {
	try {
		$db = Database::getMysqlConnection();
	} catch (PDOException $e) {
		exit($e->getMessage());
	}

	$galleryManager = new GalleryManager($db);
	$userManager = new UserManager($db);

	$img = str_replace('data:image/png;base64,', '', $_POST['image']);
	$img = str_replace(' ', '+', $img);
	$img = base64_decode($img);

	if ($img = imagecreatefromstring($img)) {
		$userName = $_SESSION['user'];
		$filename = $userName . '-' . 'untitled';

		$user = $userManager->getUserByUserName($userName);
		$userId = $user->getId();
		$photoId = $galleryManager->addPhoto($userId, $filename);

		header('Content-Type: image/png');
		imagepng($img, 'photos/' . $userName . '-' . $photoId . '.png');
		imagedestroy($img);
		die(json_encode('Success'));
	}
}
die(json_encode('Failure'));


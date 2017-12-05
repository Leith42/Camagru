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

	if (strstr($_POST['image'], 'image/jpeg')) {
		$mimetype = 'image/jpeg';
	} else if (strstr($_POST['image'], 'image/png')) {
		$mimetype = 'image/png';
	} else {
		$mimetype = 'image/gif';
	}

	$img = str_replace('data:' . $mimetype . ';base64,', '', $_POST['image']);
	$img = str_replace(' ', '+', $img);
	$img = base64_decode($img);

	if ($img = imagecreatefromstring($img)) {
		$userName = $_SESSION['user'];
		$filename = $userName . '-' . 'untitled';

		$user = $userManager->getUserByUserName($userName);
		$userId = $user->getId();
		$photoId = $galleryManager->addPhoto($userId, $filename);

		header('Content-Type: ' . $mimetype);
		if ($mimetype === 'image/png') {
			imagepng($img, 'photos/' . $userName . '-' . $photoId . '.png');
		}
		else if ($mimetype == 'image/jpeg') {
			imagejpeg($img, 'photos/' . $userName . '-' . $photoId . '.jpg');
		}
		else {
			imagegif($img, 'photos/' . $userName . '-' . $photoId . '.gif');
		}
		imagedestroy($img);
		die(json_encode('Success'));
	}
}
die(json_encode('Failure'));


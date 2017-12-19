<?php
session_start();

if (!isset($_SESSION['user'])) {
	header('Location: ' . '/');
	exit();
}

require_once('../autoload.php');

use server\classes\MontageManager;
use server\classes\ImageValidityChecker;


if (isset($_POST['image']) && isset($_POST['sticker'])) {
	$montageManager = new MontageManager();
	$newImage = $montageManager->createMontageFromWebcam($_POST['image'], $_POST['sticker']);

	if ($newImage) {
		die(json_encode($newImage));
	}
} else if (isset($_FILES['image']) && isset($_POST['sticker'])) {
	// ImageValidityChecker take an integer as a limit size, here the limit is 2mb (2 000 000 bytes).
	$imageValidityChecker = new ImageValidityChecker(2000000);

	$fileErrors = $imageValidityChecker->checkFileErrors($_FILES['image']);
	$typeIsValid = $imageValidityChecker->imageTypeIsValid($_FILES['image']);
	$sizeIsValid = $imageValidityChecker->fileSizeIsValid($_FILES['image']);

	if ($sizeIsValid && $typeIsValid && $fileErrors === 0) {
		$montageManager = new MontageManager();

		try {
			$imageString = $montageManager->createMontageFromFile($_FILES['image']['tmp_name'], $_POST['sticker'], 480, 360);
		} catch (Exception $e) {
			die(json_encode($e->getMessage()));
		}

		die(json_encode($imageString));
	}
}
die(json_encode('Failure'));

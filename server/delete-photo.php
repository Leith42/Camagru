<?php
session_start();

if (!isset($_SESSION['user']) || !isset($_POST['id'])) {
	header('Location: ' . '/');
	exit();
}

require_once('../autoload.php');

use server\classes\Database;
use server\classes\GalleryManager;

try {
	$db = Database::getMysqlConnection();
} catch (PDOException $e) {
	die($e->getMessage());
}

$galleryManager = new GalleryManager($db);

if ($galleryManager->deletePhoto($_POST['id'], $_SESSION['user']) === false) {
	die(json_encode('Failure'));
}
die (json_encode('Success'));
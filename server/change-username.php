<?php
session_start();

if (!isset($_SESSION['user']) || !isset($_POST['username'])) {
	header('Location: ' . '/');
	exit();
}

require_once('../autoload.php');

use server\classes\UserManager;
use server\classes\Database;
use server\classes\SignupValidityChecker;
use server\classes\GalleryManager;

try {
	$db = Database::getMysqlConnection();
} catch (PDOException $e) {
	die($e->getMessage());
}

$userManager = new UserManager($db);
$validityChecker = new SignupValidityChecker($db);
$galleryManager = new GalleryManager($db);
$newUsername = $_POST['username'];

if ($validityChecker->userNameIsValid($newUsername) === false) {
	die(json_encode('NotValid'));
}
if ($validityChecker->userNameIsAvailable($newUsername) === false) {
	die(json_encode('NotAvailable'));
}

$user = $userManager->getUserByUserName($_SESSION['user']);
$user->setUsername($newUsername);
$userManager->updateUsername($user);
$_SESSION['user'] = $newUsername;
die(json_encode('Success'));
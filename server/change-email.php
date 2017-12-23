<?php
session_start();

if (!isset($_SESSION['user']) || !isset($_POST['email'])) {
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
$newEmail = $_POST['email'];

if ($validityChecker->emailIsValid($newEmail) === false) {
	die(json_encode('NotValid'));
}
if ($validityChecker->emailIsAvailable($newEmail) === false) {
	die(json_encode('NotAvailable'));
}

$user = $userManager->getUserByUserName($_SESSION['user']);
$user->setEmail($newEmail);
$userManager->updateEmail($user);
die(json_encode('Success'));
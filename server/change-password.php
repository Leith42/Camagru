<?php
session_start();

if (!isset($_SESSION['user']) || !isset($_POST['password']) || !isset($_POST['passwordConfirm'])) {
	header('Location: ' . '/');
	exit();
}

require_once('../autoload.php');

use server\classes\UserManager;
use server\classes\Database;

try {
	$db = Database::getMysqlConnection();
} catch (PDOException $e) {
	die($e->getMessage());
}

$userManager = new UserManager($db);
$passwordPattern = '/((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W]).{6,})/';

if ($_POST['password'] !== $_POST['passwordConfirm']) {
	die (json_encode('NotMatching'));
}
if (!preg_match($passwordPattern, $_POST['password'])) {
	die (json_encode('NotValid'));
}

$user = $userManager->getUserByUserName($_SESSION['user']);
$user->setPassword($_POST['password']);
$userManager->updatePassword($user);
die(json_encode('Success'));
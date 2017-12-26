<?php
session_start();

if (!isset($_SESSION['user']) || !isset($_GET['option'])) {
	header('Location: ../client/error.php');
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
$user = $userManager->getUserByUserName($_SESSION['user']);

if ($_GET['option'] === 'disable') {
	$user->setEmailNotification('no');
} else if ($_GET['option'] === 'enable') {
	$user->setEmailNotification('yes');
}

$userManager->updateNotification($user);
header('Location: ../client/personal.php');
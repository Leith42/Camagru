<?php
session_start();
require_once('../autoload.php');

use server\classes\UserManager;
use server\classes\Database;
use server\classes\TokenManager;

if (isset($_GET['id'])) {
	try {
		$db = Database::getMysqlConnection();
	} catch (PDOException $e) {
		exit($e->getMessage());
	}

	$token = $_GET['id'];
	$userManager = new UserManager($db);

	if (($userManager->activateAccount($token)) === true) {
		$tokenManager = new TokenManager($db);
		$tokenManager->deleteVerificationToken($token);
	}
	header('Location: ' . '/client/verified-success.php');
}
header('Location: ' . '/client/error.php');

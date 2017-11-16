<?php
require_once('autoload.php');
session_start();

use classes\UserManager;
use classes\Database;
use classes\TokenManager;

if (isset($_GET['id'])) {
	$token = $_GET['id'];

	try {
		$db = Database::getMysqlConnection();
	} catch (PDOException $e) {
		exit($e->getMessage());
	}

	$userManager = new UserManager($db);

	if (($userManager->activateAccount($token)) === true) {
		$tokenManager = new TokenManager($db);
		$tokenManager->deleteToken($token);
		header('Location: ' . '/verified-success.php');
	} else {
		header('Location: ' . '/error.php');
	}
} else {
	header('Location: ' . '/error.php');
}
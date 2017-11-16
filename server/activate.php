<?php
require_once('../autoload.php');
session_start();

use server\classes\UserManager;
use server\classes\Database;
use server\classes\TokenManager;

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
		header('Location: ' . '/client/verified-success.php');
	} else {
		header('Location: ' . '/client/error.php');
	}
} else {
	header('Location: ' . '/client/error.php');
}

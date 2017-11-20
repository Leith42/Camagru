<?php
session_start();

if (isset($_SESSION['user'])) {
	header('Location: ' . '/');
	exit();
}

require_once('../autoload.php');

use server\classes\UserManager;
use server\classes\Database;
use server\classes\TokenManager;
use server\classes\EmailManager;

if (isset($_POST['email'])) {
	try {
		$db = Database::getMysqlConnection();
	} catch (PDOException $e) {
		die(json_encode($e->getMessage()));
	}

	$emailManager = new EmailManager($db);
	$tokenManager = new TokenManager($db);
	$userManager = new UserManager($db);

	if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
		$user = $userManager->getUserByEmail($_POST['email']);
		if ($user) {
			$emailManager->sendResetPasswordEmail($user);
		}
		die(json_encode('FormIsValid'));
	}
	die(json_encode('Failure'));
} else {
	header('Location: ' . '../client/error.php');
	exit();
}

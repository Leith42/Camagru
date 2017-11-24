<?php
session_start();
require_once('../autoload.php');

if (isset($_SESSION['user'])) {
	header('Location: ' . '/');
	exit();
}

use server\classes\Database;
use server\classes\UserManager;
use server\classes\TokenManager;

if (isset($_POST['password']) && isset($_POST['passwordRepeat']) && isset($_POST['id'])) {

	try {
		$db = Database::getMysqlConnection();
	} catch (PDOException $e) {
		exit($e->getMessage());
	}

	$passwordPattern = '/((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W]).{6,})/';
	$errors = array();

	if ($_POST['password'] !== $_POST['passwordRepeat']) {
		$errors['passwordsAreMatching'] = false;
	} else {
		$errors['passwordsAreMatching'] = true;
	}
	if (preg_match($passwordPattern, $_POST['password'])) {
		$errors['passwordIsValid'] = true;
	} else {
		$errors['passwordIsValid'] = false;
	}

	foreach ($errors as $key => $value) {
		if ($value === false) {
			die (json_encode($errors));
		}
	}

	$tokenManager = new TokenManager($db);
	$token = $_POST['id'];
	$userId = $tokenManager->getIdFromResetToken($token);

	if ($userId) {
		$userManager = new UserManager($db);
		$user = $userManager->getUserById($userId);
		$user->setPassword($_POST['password']);
		$userManager->updatePassword($user);
		$tokenManager->deleteResetPasswordToken($token);
	}

	die (json_encode('FormIsValid'));
} else {
	header('Location: ' . '../client/error.php');
	exit();
}

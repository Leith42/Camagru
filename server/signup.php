<?php
session_start();

if (isset($_SESSION['user'])) {
	header('Location: ' . '/');
	exit();
}
require_once('../autoload.php');

use server\classes\Database;
use server\classes\UserManager;
use server\classes\Users;
use server\classes\SignupValidityChecker;
use server\classes\EmailManager;

if (isset($_POST['username']) && isset($_POST['email']) &&
	isset($_POST['password']) && isset($_POST['passwordRepeat'])) {

	try {
		$db = Database::getMysqlConnection();
	} catch (PDOException $e) {
		exit($e->getMessage());
	}

	$signupChecker = new SignupValidityChecker($db);

	$user = new Users(array(
		"username" => $_POST['username'],
		"password" => $_POST['password'],
		"email" => $_POST["email"],
		"passwordRepeat" => $_POST["passwordRepeat"],
	));

	$errors = $signupChecker->isValid($user);
	foreach ($errors as $key => $value) {
		if ($value === false) {
			die (json_encode($errors));
		}
	}

	$userManager = new UserManager($db);
	$emailManager = new EmailManager($db);

	$userManager->addUser($user);
	$emailManager->sendVerificationEmail($user);

	die (json_encode('FormIsValid'));
} else {
	header('Location: ' . '../client/error.php');
	exit();
}

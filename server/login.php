<?php

session_start();

if (isset($_SESSION['user'])) {
	header('Location: ' . '/');
	exit();
}
require_once('../autoload.php');

use server\classes\Database;
use server\classes\UserManager;

if (isset($_POST['username']) && isset($_POST['password'])) {

	try {
		$db = Database::getMysqlConnection();
	} catch (PDOException $e) {
		exit($e->getMessage());
	}

	$username = $_POST['username'];
	$password = $_POST['password'];
	$userManager = new UserManager($db);
	$user = $userManager->getUserByUserName($username);

	if ($user) {
		if ($userManager->userIsVerified($user))
		{
			if (password_verify($password, $user->getPassword()) === true) {
				$_SESSION['user'] = $username;
				die(json_encode('FormIsValid'));
			}
		}
		else {
			die(json_encode('NotVerified'));
		}
	}
	die(json_encode('Failure'));
} else {
	header('Location: ' . '../client/error.php');
	exit();
}

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

	$username = $_POST['username'];
	$password = $_POST['password'];

	try {
		$db = Database::getMysqlConnection();
	} catch (PDOException $e) {
		exit($e->getMessage());
	}

	$userManager = new UserManager($db);
	$user = $userManager->getUserByUserName($username);

	if ($user) {
		if (password_verify($password, $user->getPassword()) === true) {
			$_SESSION['user'] = $username;
			die(json_encode('FormIsValid'));
		}
		die(json_encode('Failure'));
	}
	die(json_encode('Failure'));
} else {
	header('Location: ' . '../client/error.php');
	exit();
}
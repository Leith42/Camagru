<?php
session_start();

if (!isset($_SESSION['user']) || !isset($_POST['photo_id'])) {
	header('Location: /client/error.php');
	exit();
}

require_once('../autoload.php');

use server\classes\LikeManager;
use server\classes\Database;
use server\classes\UserManager;

try {
	$db = Database::getMysqlConnection();
} catch (PDOException $e) {
	die($e->getMessage());
}

$likeManager = new LikeManager($db);
$userManager = new UserManager($db);

$user = $userManager->getUserByUserName($_SESSION['user']);
$likeState = $likeManager->like($_POST['photo_id'], $user->getId());

die(json_encode($likeState));
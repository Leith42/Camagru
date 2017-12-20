<?php
session_start();

if (!isset($_SESSION['user']) || !isset($_POST['comment']) || !isset($_POST['id'])) {
	header('Location: /client/error.php');
	exit();
}

require_once('../autoload.php');

use server\classes\Database;
use server\classes\UserManager;
use server\classes\CommentManager;

try {
	$db = Database::getMysqlConnection();
} catch (PDOException $e) {
	die($e->getMessage());
}

$commentManager = new CommentManager($db);
$userManager = new UserManager($db);

$user = $userManager->getUserByUserName($_SESSION['user']);
$comment = $_POST['comment'];
$photo_id = $_POST['id'];

if ($commentManager->addComment($photo_id, $user->getId(), $comment)) {
	die(json_encode('Valid'));
}
die(json_encode('Failure'));
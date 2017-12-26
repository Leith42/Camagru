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
use server\classes\EmailManager;

try {
	$db = Database::getMysqlConnection();
} catch (PDOException $e) {
	die($e->getMessage());
}

$commentManager = new CommentManager($db);
$userManager = new UserManager($db);
$emailManager = new EmailManager($db);

$user = $userManager->getUserByUserName($_SESSION['user']);
$comment = htmlspecialchars($_POST['comment']);
$photo_id = $_POST['id'];

if ($commentManager->addComment($photo_id, $user->getId(), $comment)) {
	$emailManager->sendCommentNotification($user, $photo_id);
	die(json_encode('Valid'));
}
die(json_encode('Failure'));
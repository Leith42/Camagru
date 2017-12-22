<?php
session_start();

if (!isset($_SESSION['user']) || !isset($_POST['id'])) {
	header('Location: ' . '/');
	exit();
}

require_once('../autoload.php');

use server\classes\Database;
use server\classes\CommentManager;

try {
	$db = Database::getMysqlConnection();
} catch (PDOException $e) {
	die($e->getMessage());
}

$commentManager = new CommentManager($db);

if ($commentManager->deleteComment($_POST['id'], $_SESSION['user']) === false) {
	die(json_encode('Failure'));
}
die (json_encode('Success'));
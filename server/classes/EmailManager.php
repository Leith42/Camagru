<?php

namespace server\classes;

class EmailManager
{
	/**
	 * @var \PDO
	 */
	private $db;
	private $tokenManager;

	public function __construct(\PDO $db)
	{
		$this->db = $db;
		$this->tokenManager = new TokenManager($db);
	}

	public function sendCommentNotification(Users $user, int $photo_id)
	{
		$q = $this->db->prepare('
			SELECT user_id
			FROM gallery
			WHERE id = :photo_id
			');

		$q->bindValue(':photo_id', $photo_id);
		$q->execute();

		$result = $q->fetch(\PDO::FETCH_COLUMN);

		$userManager = new UserManager($this->db);
		$photoOwner = $userManager->getUserById($result);

		if ($photoOwner->getEmailNotification() === 'yes') {
			$user = $user->getUsername();
			$to = $photoOwner->getEmail();
			$subject = "Camagru - A new comment!";
			$link = "http://localhost:8080/client/image-viewer.php?id=$photo_id";
			$body = "Hi,\n\nyour photo receive a new comment from $user:\n$link";
//			return mail($to, $subject, $body);
		}
		return false;
	}

	public function sendMentionNotification(Users $currentUser, Users $mentionedUser, int $photo_id)
	{
		if ($mentionedUser->getEmailNotification() === 'yes') {
			$user = $currentUser->getUsername();
			$to = $mentionedUser->getEmail();
			$subject = "Camagru - A new mention!";
			$link = "http://localhost:8080/client/image-viewer.php?id=$photo_id";
			$body = "Hi,\n\nYou have been mentioned by $user on this photo:\n$link";
//			return mail($to, $subject, $body);
		}
		return false;
	}

	public function sendVerificationEmail(Users $user)
	{
		$token = $this->tokenManager->createVerificationToken($user);
		$to = $user->getEmail();
		$subject = "Camagru - Account validation!";
		$link = "http://localhost:8080/server/activate.php?id=$token";
		$body = "Hi,\n\nPlease confirm your email address by clicking on this link:\n$link";
//		return mail($to, $subject, $body);
	}

	public function sendResetPasswordEmail(Users $user)
	{
		$token = $this->tokenManager->createResetPasswordToken($user);
		$to = $user->getEmail();
		$subject = "Camagru - Reset password.";
		$link = "http://localhost:8080/client/forms/reset-form.php?id=$token";
		$body = "Hi,\n\nPlease reset your password by clicking on this link:\n$link";
//		return mail($to, $subject, $body);
	}
}

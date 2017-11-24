<?php

namespace server\classes;

class EmailManager
{
	private $db;
	private $tokenManager;

	public function __construct(\PDO $db)
	{
		$this->db = $db;
		$this->tokenManager = new TokenManager($db);
	}

	public function sendVerificationEmail(Users $user)
	{
		$token = $this->tokenManager->createVerificationToken($user);
		$to = $user->getEmail();
		$subject = "Camagru - Account validation!";
		$link = "http://localhost:8080/server/activate.php?id=" . $token;
		$body = "Hi,\n\nPlease confirm your email address by clicking on this link:\n" . $link;
		return mail($to, $subject, $body);
	}

	public function sendResetPasswordEmail(Users $user)
	{
		$token = $this->tokenManager->createResetPasswordToken($user);
		$to = $user->getEmail();
		$subject = "Camagru - Reset password.";
		$link = "http://localhost:8080/client/forms/reset-form.php?id=" . $token;
		$body = "Hi,\n\nPlease reset your password by clicking on this link:\n" . $link;
		return mail($to, $subject, $body);
	}
}

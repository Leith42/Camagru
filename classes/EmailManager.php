<?php

namespace classes;

class EmailManager
{
	private $db;

	public function __construct(\PDO $db)
	{
		$this->db = $db;
	}

	public function sendVerificationEmail(Users $user, string $token)
	{
		$to = $user->getEmail();
		$subject = "Camagru - Account validation!";
		$link = "http://localhost:8080/activate.php?id=" . $token;
		$body = "Hi,\n\nPlease confirm your email address by clicking on this link:\n" . $link;
		return mail($to, $subject, $body);
	}
}
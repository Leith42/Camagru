<?php

namespace server\classes;

class TokenManager
{
	private $db;

	public function __construct(\PDO $db)
	{
		$this->db = $db;
	}

	public function createVerificationToken(Users $user)
	{
		$token = openssl_random_pseudo_bytes(16);
		$token = bin2hex($token);
		$id = $user->getId();

		$q = $this->db->prepare('
	 	  INSERT INTO validationTokens(token, id)
		  VALUES(:token, :id)
		  ');

		$q->bindValue(':token', $token);
		$q->bindValue(':id', $id);
		$q->execute();
		return $token;
	}

	public function deleteToken(string $token)
	{
		$q = $this->db->prepare('
			DELETE FROM validationTokens
			WHERE token = :token;
			');

		$q->bindValue(':token', $token);
		$q->execute();
	}
}
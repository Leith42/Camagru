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

	public function deleteVerificationToken(string $token)
	{
		$q = $this->db->prepare('
			DELETE FROM validationTokens
			WHERE token = :token;
			');

		$q->bindValue(':token', $token);
		$q->execute();
	}

	public function createResetPasswordToken(Users $user)
	{
		$token = openssl_random_pseudo_bytes(16);
		$token = bin2hex($token);
		$id = $user->getId();

		$q = $this->db->prepare('
	 	  INSERT INTO resetTokens(token, id)
		  VALUES(:token, :id)
		  ');

		$q->bindValue(':token', $token);
		$q->bindValue(':id', $id);
		$q->execute();
		return $token;
	}

	public function getIdFromVerificationToken(string $token)
	{
		if (isset($token)) {
			$q = $this->db->prepare('
			SELECT id
			FROM validationTokens
			WHERE token = :token
			');

			$q->bindValue('token', $token);
			$q->execute();

			$result = $q->fetch(\PDO::FETCH_ASSOC);
			if ($result) {
				return $result['id'];
			}
		}
		return null;
	}

	public function getIdFromResetToken(string $token)
	{
		if (isset($token)) {
			$q = $this->db->prepare('
			SELECT id
			FROM resetTokens
			WHERE token = :token
			');

			$q->bindValue('token', $token);
			$q->execute();

			$result = $q->fetch(\PDO::FETCH_ASSOC);
			if ($result) {
				return $result['id'];
			}
		}
		return null;
	}

	public function deleteResetPasswordToken($token)
	{
		$q = $this->db->prepare('
			DELETE FROM resetTokens
			WHERE token = :token;
			');

		$q->bindValue(':token', $token);
		$q->execute();
	}
}

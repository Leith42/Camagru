<?php

namespace server\classes;

use \PDO;

class UserManager
{
	/**
	 * @var PDO
	 */
	private $db;

	public function __construct($db)
	{
		$this->db = $db;
	}

	public function addUser(Users $user)
	{
		$username = $user->getUsername();
		$password = password_hash($user->getPassword(), PASSWORD_BCRYPT);
		$email = $user->getEmail();

		$q = $this->db->prepare('
	 	 	INSERT INTO users(username, password, email)
			VALUES(:username, :password, :email)
			');

		$q->bindValue(':username', $username);
		$q->bindValue(':password', $password);
		$q->bindValue(':email', $email);
		$q->execute();

		$user->setId($this->getUserId($user));
	}

	public function getUserId(Users $user)
	{
		$q = $this->db->prepare('
		SELECT id
		FROM users
		WHERE username = :username
		');

		$q->bindValue('username', $user->getUsername());
		$q->execute();

		$result = $q->fetch();
		return $result[0];
	}

	public function getUserByUserName(string $username)
	{
		$q = $this->db->prepare('
		SELECT *
		FROM users
		WHERE username = :username
		');

		$q->bindValue('username', $username);
		$q->execute();

		return ($q->fetch());
	}

	private function getIdFromToken(string $token)
	{
		if (isset($token)) {
			$q = $this->db->prepare('
			SELECT id
			FROM validationTokens
			WHERE token = :token
			');

			$q->bindValue('token', $token);
			$q->execute();

			$row = $q->fetch();
			if ($row > 0) {
				return $row[0];
			}
		}
		return null;
	}

	public function activateAccount(string $token)
	{
		$id = $this->getIdFromToken($token);

		if ($id !== null) {
			$q = $this->db->prepare('
	 	 	UPDATE users
	 	 	SET verified = :verified
	 	 	WHERE id = :id;
			');

			$q->bindValue(':id', $id);
			$q->bindValue(':verified', 'yes');
			$q->execute();
			return true;
		}
		return false;
	}
}
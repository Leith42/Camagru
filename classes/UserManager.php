<?php

namespace classes;

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
		if ($this->userIsValid($user) === true) {
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
			return true;
		}
		return false;
	}

	private function userIsValid(Users $user)
	{
		if ($this->userNameIsValid($user->getUsername()) === false ||
			$this->userNameIsAvailable($user->getUsername()) === false ||
			$this->passwordIsValid($user->getPassword()) === false ||
			$this->emailIsAvailable($user->getEmail()) === false) {
			return false;
		}
		return true;
	}

	private function userNameIsAvailable(string $username)
	{
		$q = $this->db->prepare('
		SELECT username
		FROM users
		WHERE username = :username
		');

		$q->bindValue('username', $username);
		$q->execute();

		$row = $q->fetch();
		if ($row > 0) {
			return false;
		}
		return true;
	}

	private function emailIsAvailable(string $email)
	{
		$q = $this->db->prepare('
		SELECT email
		FROM users
		WHERE email = :email
		');

		$q->bindValue('email', $email);
		$q->execute();

		$row = $q->fetch();
		if ($row > 0) {
			return false;
		}
		return true;
	}

	private function userNameIsValid(string $username)
	{
		$userNamePattern = '/^[a-zA-Z0-9]{3,12}$/';

		if (preg_match($userNamePattern, $username)) {
			return true;
		}
		return false;
	}

	private function passwordIsValid(string $password)
	{
		$passwordPattern = '/((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W]).{6,20})/';

		if (preg_match($passwordPattern, $password)) {
			return true;
		}
		return false;
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
<?php

namespace classes;

use \PDO;

class UserManager
{
	/**
	 * @var PDO
	 */
	private $_db;

	public function __construct($db)
	{
		$this->_db = $db;
	}

	public function addUser(Users $user)
	{
		if ($this->userIsValid($user) === true) {
			$username = $user->getUsername();
			$password = password_hash($user->getPassword(), PASSWORD_BCRYPT);
			$email = $user->getEmail();

			$q = $this->_db->prepare('
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
			$this->passwordIsValid($user->getPassword()) === false) {
			return false;
		}
		return true;
	}

	private function userNameIsAvailable(string $username)
	{
		$q = $this->_db->prepare('
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
		$q = $this->_db->prepare('
		SELECT id
		FROM users
		WHERE username = :username
		');

		$q->bindValue('username', $user->getUsername());
		$q->execute();

		$result = $q->fetch();
		return $result[0];
	}
}
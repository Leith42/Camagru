<?php

namespace server\classes;

class SignupValidityChecker
{
	private $db;
	private $error = array();

	public function __construct(\PDO $db)
	{
		$this->db = $db;
	}

	public function isValid(Users $user)
	{
		$username = $user->getUsername();
		$password = $user->getPassword();
		$passwordRepeat = $user->getPasswordRepeat();
		$email = $user->getEmail();

		if ($this->userNameIsValid($username)) {
			$this->userNameIsAvailable($username);
		}
		$this->passwordIsValid($password, $passwordRepeat);
		if ($this->emailIsValid($email)) {
			$this->emailIsAvailable($email);
		}
		return $this->error;
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
			$this->error['emailAvailable'] = false;
		} else {
			$this->error['emailAvailable'] = true;
		}
	}

	private function userNameIsValid(string $username)
	{
		$userNamePattern = '/^[a-zA-Z0-9]{3,12}$/';

		if (preg_match($userNamePattern, $username)) {
			$this->error['usernameIsValid'] = true;
			return true;
		} else {
			$this->error['usernameIsValid'] = false;
			return false;
		}
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
			$this->error['usernameIsAvailable'] = false;
			return false;
		}
		$this->error['usernameIsAvailable'] = true;
		return true;
	}

	private function emailIsValid(string $email)
	{
		if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$this->error['emailIsValid'] = true;
			return true;
		}
		$this->error['emailIsValid'] = false;
		return false;
	}

	private function passwordIsValid(string $password, string $passwordRepeat)
	{
		$passwordPattern = '/((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W]).{6,})/';

		if ($password !== $passwordRepeat) {
			$this->error['passwordsAreMatching'] = false;
		} else {
			$this->error['passwordsAreMatching'] = true;
		}
		if (preg_match($passwordPattern, $password)) {
			$this->error['passwordIsValid'] = true;
		} else {
			$this->error['passwordIsValid'] = false;
		}
	}
}
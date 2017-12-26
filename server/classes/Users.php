<?php

namespace server\classes;

class Users
{
	private $id;
	private $username;
	private $password;
	private $passwordRepeat;
	private $email;
	private $emailNotification;

	public function __construct(array $userInfos)
	{
		foreach ($userInfos as $attribute => $value) {
			$method = 'set' . ucfirst($attribute);
			if (is_callable([$this, $method])) {
				$this->$method($value);
			}
		}
	}

	public function getEmailNotification()
	{
		return $this->emailNotification;
	}

	public function setEmailNotification($emailNotification)
	{
		$this->emailNotification = $emailNotification;
	}

	public function getPasswordRepeat()
	{
		return $this->passwordRepeat;
	}

	public function setPasswordRepeat($passwordRepeat)
	{
		$this->passwordRepeat = $passwordRepeat;
	}

	public function getEmail()
	{
		return $this->email;
	}

	public function setEmail($email)
	{
		$this->email = $email;
	}

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getUsername()
	{
		return $this->username;
	}

	public function setUsername($username)
	{
		$this->username = $username;
	}

	public function setPassword($password)
	{
		$this->password = $password;
	}

	public function getPassword()
	{
		return $this->password;
	}
}

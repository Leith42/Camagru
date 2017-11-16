<?php

namespace server\classes;

class Users
{
	private $_id;
	private $_username;
	private $_password;
	private $_email;

	public function __construct(array $userInfos)
	{
		foreach ($userInfos as $attribute => $value) {
			$method = 'set' . ucfirst($attribute);
			if (is_callable([$this, $method])) {
				$this->$method($value);
			}
		}
	}

	public function getEmail()
	{
		return $this->_email;
	}

	public function setEmail($email)
	{
		$this->_email = $email;
	}

	public function getId()
	{
		return $this->_id;
	}

	public function setId($id)
	{
		$this->_id = $id;
	}

	public function getUsername()
	{
		return $this->_username;
	}

	public function setUsername($username)
	{
		$this->_username = $username;
	}

	public function setPassword($password)
	{
		$this->_password = $password;
	}

	public function getPassword()
	{
		return $this->_password;
	}

	public function isNew()
	{
		return empty($this->_id);
	}
}
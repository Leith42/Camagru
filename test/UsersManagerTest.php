<?php

namespace classes;

//require_once('/Database.php');
//require_once('/classes/Users.php');
//require_once('/UserManager.php');

use PHPUnit\Framework\TestCase;

class UserManagerTest extends TestCase
{
	private $_db;
	private $_userManager;

	public function __construct($name = null, array $data = [], $dataName = '')
	{
		parent::__construct($name, $data, $dataName);
		$this->_db = Database::getMysqlConnection();
		$this->_userManager = new UserManager($this->_db);
	}

	public function testAddUserDuplicate()
	{
		$user = new Users([
			'username' => 'Admin',
			'password' => 'Password1!'
		]);

		$result = $this->_userManager->addUser($user);
		$this->assertEquals(false, $result);
	}

	public function testAddUserInvalidUsername()
	{
		$user = new Users([
			'username' => '',
			'password' => 'Password1!'
		]);

		$invalidUserName[] = '';
		$invalidUserName[] = 'po';
		$invalidUserName[] = 'user!';
		$invalidUserName[] = 'dsfiodsjfsdoifjsdoijofsdsdfkiopsdak';
		$invalidUserName[] = '#!&@^!@&*^#(!*#&!(*@#&^#';

		foreach ($invalidUserName as $username) {
			$user->setUsername($username);
			$result = $this->_userManager->addUser($user);
			$this->assertEquals(false, $result);
		}
	}

	public function testAddUserInvalidPassword()
	{
		$user = new Users([
			'username' => 'pseudololz',
			'password' => ''
		]);

		$invalidPasswords[] = '';
		$invalidPasswords[] = 'poo';
		$invalidPasswords[] = 'invalid';
		$invalidPasswords[] = 'Invalid';
		$invalidPasswords[] = 'Invalid2';
		$invalidPasswords[] = 'Invalid!';
		$invalidPasswords[] = '123a!@#';
		$invalidPasswords[] = '123A!@#';

		foreach ($invalidPasswords as $password) {
			$user->setPassword($password);
			$result = $this->_userManager->addUser($user);
			$this->assertEquals(false, $result);
		}
	}

	public function testAddUserValidPassword()
	{
		$user = new Users([
			'username' => 'pseudololz',
			'password' => ''
		]);

		$invalidPasswords[] = 'Password!4';
		$invalidPasswords[] = 'lolKva#23';
		$invalidPasswords[] = 'Aa!1234567';
		$invalidPasswords[] = '1561158a151V@#';
		$invalidPasswords[] = 'aaaaAAAA11$@(#';

		foreach ($invalidPasswords as $password) {
			$user->setPassword($password);
			$result = $this->_userManager->addUser($user);
			$this->assertEquals(true, $result);
		}
	}

	public function testAddUserValidUsername()
	{
		$user = new Users([
			'username' => '',
			'password' => 'Password!2'
		]);

		$validUserName[] = 'BernardTapis';
		$validUserName[] = 'Josephine';
		$validUserName[] = 'flew1337';
		$validUserName[] = 'max';
		$validUserName[] = 'BigPseudoLol';

		foreach ($validUserName as $username) {
			$user->setUsername($username);
			$result = $this->_userManager->addUser($user);
			$this->assertEquals(true, $result);
		}
	}
}

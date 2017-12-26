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

	private function getUserId(Users $user)
	{
		$q = $this->db->prepare('
		SELECT id
		FROM users
		WHERE username = :username
		');

		$q->bindValue('username', $user->getUsername());
		$q->execute();

		$result = $q->fetch(PDO::FETCH_ASSOC);
		return $result['id'];
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
		$result = $q->fetch(PDO::FETCH_ASSOC);

		if ($result) {
			return new Users($result);
		}
		return false;
	}

	public function getUserById(int $id)
	{
		$q = $this->db->prepare('
		SELECT *
		FROM users
		WHERE id = :id
		');

		$q->bindValue('id', $id);
		$q->execute();
		$result = $q->fetch(PDO::FETCH_ASSOC);

		if ($result) {
			return new Users($result);
		}
		return null;
	}

	public function getUserByEmail(string $email)
	{
		$q = $this->db->prepare('
		SELECT *
		FROM users
		WHERE email = :email
		');

		$q->bindValue('email', $email);
		$q->execute();
		$result = $q->fetch(PDO::FETCH_ASSOC);

		if ($result) {
			return new Users($result);
		}
		return null;
	}

	public function updateNotification(Users $user)
	{
		$q = $this->db->prepare('
			UPDATE users
			SET emailNotification = :emailNotification
			WHERE id = :id
		');

		$emailNotification = $user->getEmailNotification();
		$id = $user->getId();

		$q->bindValue(':emailNotification', $emailNotification);
		$q->bindValue(':id', $id);
		$q->execute();
	}

	public function updateEmail(Users $user)
	{
		$q = $this->db->prepare('
	 	 	UPDATE users
	 	 	SET email = :email
	 	 	WHERE id = :id
			');

		$newEmail = $user->getEmail();
		$id = $user->getId();

		$q->bindValue(':email', $newEmail);
		$q->bindValue(':id', $id);
		$q->execute();
	}

	public function updatePassword(Users $user)
	{
		$q = $this->db->prepare('
	 	 	UPDATE users
	 	 	SET password = :password
	 	 	WHERE id = :id;
			');

		$newPassword = password_hash($user->getPassword(), PASSWORD_BCRYPT);
		$id = $user->getId();

		$q->bindValue(':password', $newPassword);
		$q->bindValue(':id', $id);
		$q->execute();
	}

	public function updateUsername(Users $user)
	{
		$q = $this->db->prepare('
	 	 	UPDATE users
	 	 	SET username = :username
	 	 	WHERE id = :id;
			');

		$newUsername = $user->getUsername();
		$id = $user->getId();

		$q->bindValue(':username', $newUsername);
		$q->bindValue(':id', $id);
		$q->execute();
	}

	public function activateAccount(string $token)
	{
		$tokenManager = new TokenManager($this->db);

		$id = $tokenManager->getIdFromVerificationToken($token);
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

	public function userIsVerified(Users $user)
	{
		$q = $this->db->prepare('
		SELECT verified
		FROM users
		WHERE id = :id
		');

		$q->bindValue('id', $user->getId());
		$q->execute();
		$result = $q->fetch(PDO::FETCH_ASSOC);

		if ($result['verified'] === 'yes') {
			return true;
		}
		return false;
	}
}

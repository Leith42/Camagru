<?php

namespace server\classes;

class LikeManager
{
	private $db;

	public function __construct(\PDO $db)
	{
		$this->db = $db;
	}

	public function like(int $photo_id, int $user_id)
	{
		$likeState = $this->likeCurrentState($photo_id, $user_id);

		if ($likeState === 'active') {
			$this->removeLike($photo_id, $user_id);
		} else {
			$this->addLike($photo_id, $user_id);
		}

		return $likeState;
	}

	public function likeCurrentState(int $photo_id, int $user_id)
	{
		$q = $this->db->prepare('
		SELECT *
		FROM likes
		WHERE user_id = :user_id AND photo_id = :photo_id
		');

		$q->bindValue(':user_id', $user_id);
		$q->bindValue(':photo_id', $photo_id);
		$q->execute();

		$result = $q->fetch(\PDO::FETCH_COLUMN);

		if ($result) {
			return 'active';
		}
		return 'inactive';
	}

	private function addLike(int $photo_id, int $user_id)
	{
		$q = $this->db->prepare('
	 	INSERT INTO likes(user_id, photo_id)
	 	VALUES(:user_id, :photo_id)
		');

		$q->bindValue(':user_id', $user_id);
		$q->bindValue(':photo_id', $photo_id);
		$q->execute();
	}

	private function removeLike(int $photo_id, int $user_id)
	{
		$q = $this->db->prepare('
		DELETE FROM likes
		WHERE photo_id = :photo_id AND user_id = :user_id
		');

		$q->bindValue(':user_id', $user_id);
		$q->bindValue(':photo_id', $photo_id);
		$q->execute();
	}

	public function getLikeCounter(int $photo_id)
	{
		$q = $this->db->prepare('
		SELECT COUNT(*)
		FROM likes
		WHERE photo_id = :photo_id
		');

		$q->bindValue(':photo_id', $photo_id);
		$q->execute();

		return $q->fetch(\PDO::FETCH_COLUMN);
	}
}
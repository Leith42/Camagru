<?php

namespace server\classes;

class GalleryManager
{
	/**
	 * @var \PDO
	 */
	private $db;

	public function __construct(\PDO $db)
	{
		$this->db = $db;
	}

	public function addPhoto(int $userId, string $filename)
	{
		$q = $this->db->prepare('
	 	 	INSERT INTO gallery(user_id, filename)
			VALUES(:user_id, :filename)
			');

		$q->bindValue(':user_id', $userId);
		$q->bindValue(':filename', $filename);
		$q->execute();

		$id = $this->db->lastInsertId();
		return ($id);
	}

	private function prepareImage($image)
	{

	}
}
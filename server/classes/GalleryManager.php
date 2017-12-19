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

	private function getPhotos(int $page, int $per_page)
	{
		$q = $this->db->prepare('
		SELECT *
		FROM gallery
		LIMIT :skip, :per_page
		');

		$skip = $page * $per_page;

		$q->bindValue(':skip', $skip, \PDO::PARAM_INT);
		$q->bindValue(':per_page', $per_page, \PDO::PARAM_INT);
		$q->execute();

		$result = $q->fetchAll(\PDO::FETCH_ASSOC);
		return $result;
	}

	private function getStringTypeOfFile(string $filename)
	{
		if (file_exists('../server/photos/' . $filename . '.jpeg') ||
			file_exists('../server/photos/' . $filename . '.jpg')) {
			return '.jpg';
		} else if (file_exists('../server/photos/' . $filename . '.png')) {
			return '.png';
		} else if (file_exists('../server/photos/' . $filename . '.gif')) {
			return '.gif';
		}
		return null;
	}

	public function printGallery(int $page, int $per_page)
	{
		$userManager = new UserManager($this->db);
		$photos = $this->getPhotos($page, $per_page);
		$count = 0;

		echo '<div id="gallery">';
		foreach ($photos as $photo) {
			if ($count++ === 4) {
				echo '<br />';
				$count = 0;
			}

			$user = $userManager->getUserById($photo['user_id']);
			$filename = $user->getUsername() . '-' . $photo['id'];
			$type = $this->getStringTypeOfFile($filename);

			echo '<a href="/client/view-image.php?id=' . $photo['id'] . '">';
			echo '<img id="gallery-photos" src="/server/photos/' . $filename . $type . '">';
			echo '</a>';
		}
		echo '</div>';
	}
}
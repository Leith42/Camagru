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

		if ($page === 1) {
			$skip = 0;
		} else {
			$skip = ($page - 1) * $per_page;
		}

		$q->bindValue(':skip', $skip, \PDO::PARAM_INT);
		$q->bindValue(':per_page', $per_page + 1, \PDO::PARAM_INT);
		$q->execute();

		$result = $q->fetchAll(\PDO::FETCH_ASSOC);
		return $result;
	}

	private function getPhotoById(int $id)
	{
		$q = $this->db->prepare('
		SELECT *
		FROM gallery
		WHERE id = :id;
		');

		$q->bindValue(':id', $id);
		$q->execute();

		$result = $q->fetch(\PDO::FETCH_ASSOC);
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

	public function printPhoto(int $id)
	{
		$photo = $this->getPhotoById($id);

		if (!$photo) {
			header('Location: /client/error.php');
			exit();
		}

		$userManager = new UserManager($this->db);
		$user = $userManager->getUserById($photo['user_id']);
		$filename = $user->getUsername() . '-' . $photo['id'];
		$type = $this->getStringTypeOfFile($filename);

		echo '<div id="image-viewer">';
		echo '<img id="photo-big" src="/server/photos/' . $filename . $type . '">';
		echo '<span id="signature">' . 'Posted by ' . $user->getUsername() . '.';
		echo '</div>';
	}

	public function printGallery(int $page, int $per_page)
	{
		$userManager = new UserManager($this->db);
		$photos = $this->getPhotos($page, $per_page);
		$count = 0;
		$has_next = false;

		if (sizeof($photos) > $per_page) {
			$has_next = true;
			array_pop($photos);
		}

		echo '<div id="gallery">';
		foreach ($photos as $photo) {
			if ($count++ === 4) {
				echo '<br />';
				$count = 0;
			}

			$user = $userManager->getUserById($photo['user_id']);
			$filename = $user->getUsername() . '-' . $photo['id'];
			$type = $this->getStringTypeOfFile($filename);

			echo '<a href="/client/image-viewer.php?id=' . $photo['id'] . '">';
			echo '<div id="gallery-photos-block">';
			echo '<img id="gallery-photos" src="/server/photos/' . $filename . $type . '">';
			echo '<span id="signature">' . $user->getUsername();
			echo '</span></div></a>';
		}
		$this->printPagination($page, $has_next);
	}

	private function printPagination (int $page, bool $has_next)
	{
		echo '<br />';
		echo '<div id="page-number">Page ' . $page . '</div>';
		if ($page > 1) {
			echo '<a class="pagination" style="text-decoration: none" href="/client/gallery.php?page=' . ($page - 1) . '">Back</a>';
		}
		if ($has_next) {
			echo '<a class="pagination" style="text-decoration: none" href="/client/gallery.php?page=' . ($page + 1) . '">Next</a>';
		}
		echo '</div>';
	}
}
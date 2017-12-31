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

	private function checkRightsForDelete(int $photo_id, string $currentUserName)
	{
		$userManager = new UserManager($this->db);
		$currentUser = $userManager->getUserByUserName($currentUserName);
		$currentUserId = $currentUser->getId();

		$q = $this->db->prepare('
			SELECT user_id
			FROM gallery
			WHERE id = :photo_id
		');

		$q->bindValue(':photo_id', $photo_id);
		$q->execute();

		$result = $q->fetch(\PDO::FETCH_ASSOC);

		if ($result['user_id'] === $currentUserId) {
			return true;
		}
		return false;
	}

	public function deletePhoto(int $photo_id, string $currentUserName)
	{
		$userHasRights = $this->checkRightsForDelete($photo_id, $currentUserName);

		if ($userHasRights) {
			$userManager = new UserManager($this->db);
			$currentUser = $userManager->getUserByUserName($currentUserName);
			$userId = $currentUser->getId();

			if (file_exists('../server/photos/' . $userId . '-' . $photo_id . '.jpg')) {
				unlink('../server/photos/' . $userId . '-' . $photo_id . '.jpg');
			} else if (file_exists('../server/photos/' . $userId . '-' . $photo_id . '.png')) {
				unlink('../server/photos/' . $userId . '-' . $photo_id . '.png');
			} else if (file_exists('../server/photos/' . $userId . '-' . $photo_id . '.gif')) {
				unlink('../server/photos/' . $userId . '-' . $photo_id . '.gif');
			}

			$q = $this->db->prepare('
			DELETE FROM gallery
			WHERE id = :photo_id
			');

			$q->bindValue(':photo_id', $photo_id);
			$q->execute();
		}
		return $userHasRights;
	}

	private function getLastPhotos(string $currentUserName)
	{
		$userManager = new UserManager($this->db);
		$currentUser = $userManager->getUserByUserName($currentUserName);
		$userId = $currentUser->getId();

		$q = $this->db->prepare('
		SELECT *
		FROM gallery
	  	WHERE user_id = :id
	  	ORDER BY id DESC
		LIMIT 3
		');

		$q->bindValue(':id', $userId);
		$q->execute();

		$result = $q->fetchAll(\PDO::FETCH_ASSOC);
		return $result;
	}

	public function printLastPhotos(string $currentUserName)
	{
		$photos = $this->getLastPhotos($currentUserName);

		if ($photos) {
			$userManager = new UserManager($this->db);
			$user = $userManager->getUserByUserName($currentUserName);
			$userId = $user->getId();
			foreach ($photos as $photo) {
				$filename = $userId . '-' . $photo['id'];
				$type = $this->getStringTypeOfFile($filename);

				echo '<a href="/client/image-viewer.php?id=' . $photo['id'] . '">';
				echo '<div id="last-photos-block">';
				echo '<img id="last-photos" src="/server/photos/' . $filename . $type . '">';
				echo '</div></a>';
			}
		} else {
			echo 'Your last uploaded photos will be displayed here.';
		}
	}

	private function getPhotos(int $page, int $per_page)
	{
		$q = $this->db->prepare('
		SELECT *
		FROM gallery
		ORDER BY id DESC
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

	private function getPersonalPhotos(int $page, int $per_page, $currentUserName)
	{
		$userManager = new UserManager($this->db);
		$user = $userManager->getUserByUserName($currentUserName);
		$userId = $user->getId();

		$q = $this->db->prepare('
		SELECT *
		FROM gallery
		WHERE user_id = :userId
		LIMIT :skip, :per_page
		');

		if ($page === 1) {
			$skip = 0;
		} else {
			$skip = ($page - 1) * $per_page;
		}

		$q->bindValue(':skip', $skip, \PDO::PARAM_INT);
		$q->bindValue(':per_page', $per_page + 1, \PDO::PARAM_INT);
		$q->bindValue(':userId', $userId);
		$q->execute();

		$result = $q->fetchAll(\PDO::FETCH_ASSOC);
		return $result;
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
		$filename = $user->getId() . '-' . $photo['id'];
		$type = $this->getStringTypeOfFile($filename);

		if (isset($_SESSION['user'])) {
			$currentUser = $userManager->getUserByUserName($_SESSION['user']);
		}

		echo '<div id="image-viewer">';
		echo '<img id="photo-big" src="/server/photos/' . $filename . $type . '">';
		echo '<div id="signature">' . 'Posted by ' . $user->getUsername() . '</div>';
		if (isset($currentUser) && $currentUser->getId() === $photo['user_id']) {
			echo '<input name="delete-photo-button" id="delete-photo-button" type="image" src="/client/img/delete-photo.png" value="' . $photo['id'] . '"/>';
		}

		$likeManager = new LikeManager($this->db);
		$likeCounter = $likeManager->getLikeCounter($photo['id']);

		if (isset($currentUser)) {
			if ($likeManager->likeCurrentState($photo['id'], $currentUser->getId()) === 'active') {
				echo '<input name="like-button" id="like-button" type="image" src="/client/img/like-red.png" value="' . $photo['id'] . '"/>';
			} else {
				echo '<input name="like-button" id="like-button" type="image" src="/client/img/like-black.png" value="' . $photo['id'] . '"/>';
			}
		} else {
			echo '<img name="like-button" id="like-inactive" src="/client/img/like-black.png"/>';
		}
		echo '<div id="like-counter">' . $likeCounter . '</div>';
		echo '<a href="https://twitter.com/share?ref_src=twsrc%5Etfw" class="twitter-share-button" data-text="Best camagru ever!" data-hashtags="camagru" data-show-count="false">Tweet</a><script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>';
		echo '</div>';
	}

	public function printPersonalGallery(int $page, int $per_page, string $currentUserName)
	{
		$userManager = new UserManager($this->db);
		$photos = $this->getPersonalPhotos($page, $per_page, $currentUserName);
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
			$filename = $user->getId() . '-' . $photo['id'];
			$type = $this->getStringTypeOfFile($filename);

			echo '<a href="/client/image-viewer.php?id=' . $photo['id'] . '">';
			echo '<div id="gallery-photos-block">';
			echo '<img id="gallery-photos" src="/server/photos/' . $filename . $type . '">';
			echo '<span id="signature-gallery">' . $user->getUsername();
			echo '</span></div></a>';
		}
		$this->printPersonalPagination($page, $has_next);
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
			$filename = $user->getId() . '-' . $photo['id'];
			$type = $this->getStringTypeOfFile($filename);

			echo '<a href="/client/image-viewer.php?id=' . $photo['id'] . '">';
			echo '<div id="gallery-photos-block">';
			echo '<img id="gallery-photos" src="/server/photos/' . $filename . $type . '">';
			echo '<span id="signature-gallery">' . $user->getUsername();
			echo '</span></div></a>';
		}
		$this->printPagination($page, $has_next);
	}

	private function printPagination(int $page, bool $has_next)
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

	private function printPersonalPagination(int $page, bool $has_next)
	{
		echo '<br />';
		echo '<div id="page-number">Page ' . $page . '</div>';
		if ($page > 1) {
			echo '<a class="pagination" style="text-decoration: none" href="/client/my-gallery.php?page=' . ($page - 1) . '">Back</a>';
		}
		if ($has_next) {
			echo '<a class="pagination" style="text-decoration: none" href="/client/my-gallery.php?page=' . ($page + 1) . '">Next</a>';
		}
		echo '</div>';
	}

	private function getPhotoById(int $id)
	{
		$q = $this->db->prepare('
		SELECT *
		FROM gallery
		WHERE id = :id
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
}
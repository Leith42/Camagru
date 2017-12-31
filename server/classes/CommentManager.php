<?php

namespace server\classes;

class CommentManager
{
	private $db;

	public function __construct(\PDO $db)
	{
		$this->db = $db;
	}

	private function getComments(int $photo_id)
	{
		$q = $this->db->prepare('
			SELECT *
			FROM comments
			WHERE photo_id = :photo_id
		');

		$q->bindValue(':photo_id', $photo_id);
		$q->execute();

		return $q->fetchAll(\PDO::FETCH_ASSOC);
	}

	private function printCommentInput()
	{
		echo '<div id="wrapper-comment-input">';
		echo '<input class="comment-input" type="text" placeholder="Your comment..." name="comment-input" required>';
		echo '<button type="submit" class="button" name="submit-button">Send</button>';
		echo '</div>';
	}

	private function checkRightsForDelete(int $comment_id, string $currentUserName)
	{
		$userManager = new UserManager($this->db);
		$currentUser = $userManager->getUserByUserName($currentUserName);
		$currentUserId = $currentUser->getId();

		$q = $this->db->prepare('
			SELECT user_id
			FROM comments
			WHERE id = :comment_id
		');

		$q->bindValue(':comment_id', $comment_id);
		$q->execute();

		$result = $q->fetch(\PDO::FETCH_ASSOC);

		if ($result['user_id'] === $currentUserId) {
			return true;
		}
		return false;
	}

	public function deleteComment(int $comment_id, string $currentUserName)
	{
		$userHasRights = $this->checkRightsForDelete($comment_id, $currentUserName);

		if ($userHasRights) {
			$q = $this->db->prepare('
			DELETE FROM comments
			WHERE id = :comment_id
			');

			$q->bindValue(':comment_id', $comment_id);
			$q->execute();
		}
		return $userHasRights;
	}

	public function printComments(int $photo_id)
	{
		$userManager = new UserManager($this->db);
		$comments = $this->getComments($photo_id);

		if (isset($_SESSION['user'])) {
			$currentUser = $userManager->getUserByUserName($_SESSION['user']);
		}

		echo '<div id="comment-block">';
		if ($comments) {
			foreach ($comments as $comment) {
				$author = $userManager->getUserById($comment['user_id']);

				//Print author
				echo '<div class="comment-author">' . $author->getUsername() . '<br /></div>';

				//Print options
				if (isset($currentUser) && $currentUser->getId() === $author->getId()) {
					echo '<input name="delete-comment-button" class="delete-comment-button" type="image" src="/client/img/delete.png" value="' . $comment['id'] . '"/>';
				}

				//Print comment
				echo '<div class="comment">' . $comment['comment'] . '</div>';
			}
		} else {
			echo 'Be the first to comment!';
		}
		echo '</div>';
		if (isset($currentUser)) {
			$this->printCommentInput();
		}
	}

	private function addMentions(string $comment, int $photo_id)
	{
		preg_match_all('/\B\@\w+/', $comment, $matches);

		if ($matches) {
			$userManager = new UserManager($this->db);
			$emailManager = new EmailManager($this->db);
			$currentUser = $userManager->getUserByUserName($_SESSION['user']);
			$matches = array_unique($matches[0]);

			foreach ($matches as $match) {
				$username = substr($match, 1);
				$mentionedUser = $userManager->getUserByUserName($username);

				if ($mentionedUser) {
					$emailManager->sendMentionNotification($currentUser, $mentionedUser, $photo_id);
					$comment = str_replace($match, '<span style="color: blue">' . $match . '</span>', $comment);
				}
			}
		}
		return $comment;
	}

	public function addComment(int $photo_id, int $user_id, string $comment)
	{
		$comment = $this->addMentions($comment, $photo_id);

		$len = strlen($comment);
		if ($len === 0 || $len > 160) {
			return false;
		}

		$q = $this->db->prepare('
	 	 	INSERT INTO comments(photo_id, user_id, comment)
			VALUES(:photo_id, :user_id, :comment)
			');

		$q->bindValue(':photo_id', $photo_id);
		$q->bindValue(':user_id', $user_id);
		$q->bindValue(':comment', $comment);

		$q->execute();
		return true;
	}
}
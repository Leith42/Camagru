<?php

if (php_sapi_name() != 'cli') {
	exit ('nope, CLI only.');
}

require_once '../autoload.php';

use server\classes\Database;

$db_infos = (include 'database.php');

echo $db_infos['DB_NAME'] . '\'s database is initializing, please wait a few seconds...' . PHP_EOL;

try {
	$pdo = new PDO('mysql:host=' . $db_infos['DB_HOST'], $db_infos['DB_USER'], $db_infos['DB_PASSWORD']);
	$pdo->exec('DROP DATABASE IF EXISTS ' . $db_infos['DB_NAME']);

	if (!$pdo->exec('CREATE DATABASE IF NOT EXISTS ' . $db_infos['DB_NAME'] . ' CHARACTER SET utf8 COLLATE utf8_general_ci;')) {
		exit(print_r($pdo->errorInfo(), true));
	}

	$db = Database::getMysqlConnection();

	$db->exec('
-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` INT(11) NOT NULL,
  `date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `photo_id` INT(11) NOT NULL,
  `user_id` INT(11) NOT NULL,
  `comment` TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `id` BIGINT(20) UNSIGNED NOT NULL,
  `filename` VARCHAR(25) DEFAULT NULL,
  `user_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT \'0\' COMMENT \'owner\'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` INT(11) NOT NULL,
  `user_id` INT(11) NOT NULL,
  `photo_id` INT(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `resetTokens`
--

CREATE TABLE `resetTokens` (
  `token` CHAR(64) NOT NULL,
  `id` INT(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` INT(11) UNSIGNED NOT NULL,
  `username` CHAR(12) NOT NULL,
  `password` CHAR(72) NOT NULL COMMENT \'Blowfish\',
  `email` CHAR(50) NOT NULL,
  `emailNotification` ENUM(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  `rights` ENUM(\'admin\',\'member\') CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT \'member\',
  `verified` ENUM(\'yes\',\'no\') NOT NULL DEFAULT \'no\'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `emailNotification`, `rights`, `verified`) VALUES
(1, \'admin\', \'$2y$10$/8m6YOKKuHywfPMB1jx/8ehoK2Fs0lewf8vFjnyYAofoJlEy0xbhq\', \'aazri@student.42.fr\', \'yes\', \'admin\', \'yes\');

-- --------------------------------------------------------

--
-- Table structure for table `validationTokens`
--

CREATE TABLE `validationTokens` (
  `token` CHAR(64) NOT NULL,
  `id` INT(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `resetTokens`
--
ALTER TABLE `resetTokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `validationTokens`
--
ALTER TABLE `validationTokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `token` (`token`);

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;
COMMIT;
');
<<<<<<< HEAD
} catch (PDOException $e) {
	exit($e->getMessage());
}

echo 'the database is now correctly initialized, enjoy!' . PHP_EOL;
=======

	echo 'the database is now correctly initialized, enjoy!' . PHP_EOL;
} catch (PDOException $e) {
	exit($e->getMessage());
}
>>>>>>> master

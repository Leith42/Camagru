<?php

namespace server\classes;

class Database
{
	static function getMysqlConnection()
	{
		$dsn = 'mysql:dbname=camagru;host=localhost';
		$user = 'root';
		$password = 'lol';

		$db = new \PDO($dsn, $user, $password);
		$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		return $db;
	}
}

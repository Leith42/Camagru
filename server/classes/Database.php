<?php

namespace server\classes;

class Database
{
	static function getMysqlConnection()
	{
		$db_infos = (include '../config/database.php');
		$dsn = $db_infos['DB_DSN'];
		$user = $db_infos['DB_USER'];
		$password = $db_infos['DB_PASSWORD'];

		$db = new \PDO($dsn, $user, $password, array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
		$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		return $db;
	}
}

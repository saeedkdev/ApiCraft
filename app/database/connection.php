<?php 

namespace App\Database;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Connection;

require_once __DIR__ . '/../../config/config.php';

class DatabaseConnection {

	/**
	 * @var Connection
	 */
	
	private static $conn;

	public static function getInstance(): Connection {

		if (self::$conn) {
            return self::$conn;
		}

		$config = new \Doctrine\DBAL\Configuration();

		# set up connection parameters
		$connectionParams = array(
			'dbname' => DB_NAME,
			'user' => DB_USER,
			'password' => DB_PASS,
			'host' => DB_HOST,
			'driver' => 'pdo_mysql',
		);

		# obtain the entity manager
		self::$conn = DriverManager::getConnection($connectionParams, $config);

		return self::$conn;
	}

	public static function getConnection(): Connection {
		return self::getInstance();
	}

}

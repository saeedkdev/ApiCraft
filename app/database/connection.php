<?php 

namespace App\Database;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Connection;
use Psr\Log\LoggerInterface;

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

    public static function getQueryBuilder(): \Doctrine\DBAL\Query\QueryBuilder {
        return self::getInstance()->createQueryBuilder();
    }

    public static function getSchemaManager(): \Doctrine\DBAL\Schema\AbstractSchemaManager {
        return self::getInstance()->createSchemaManager();
    }

    public static function getSchema(): \Doctrine\DBAL\Schema\Schema {
        return self::getInstance()->createSchemaManager()->introspectSchema();
    }

    // function to get logger with type LoggerInterface
    public static function getLogger(): LoggerInterface {
        return new \Psr\Log\NullLogger();
    }

}

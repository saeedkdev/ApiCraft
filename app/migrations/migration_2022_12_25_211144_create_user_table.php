<?php

namespace App\Migrations;

use	App\Database\DatabaseConnection;
use	Doctrine\DBAL\Schema\Schema;
use	Doctrine\Migrations\AbstractMigration;

class Migration_2022_12_25_211144_create_user_table extends AbstractMigration {

	public function __construct()
	{
		$this->connection = DatabaseConnection::getConnection();
		$this->logger = NULL;
	}

	public function getTables() {
		$tables = $this->connection->getSchemaManager()->listTables();
		return $tables;
	}

	public function modifyOrAddColumn($column) : bool {
		$schema = $this->connection->createSchemaManager()->introspectSchema();
		$table = $schema->getTable('user');
		if ($table->hasColumn($column)) {
			return true;
		} else {
			return true;
		}
	}

	public function up(Schema $schema): void {
		# check if table already exists
		$tables = $this->connection->createSchemaManager()->listTables();
		# loop through tables
		$tableNames = [];
		foreach ($tables as $table) {
			$tableNames[] = $table->getName();
		}

		if(in_array('user', $tableNames)) {
			# update table
			$schema = $this->connection->createSchemaManager()->introspectSchema();
			$table = $schema->getTable('user');
		} else {
			# create table
			$table = $schema->createTable('user');
		}

		$table->addColumn('id', 'integer', ['autoincrement' => true]);
		$table->addColumn('name', 'string', ['length' => 255]);
		$table->addColumn('email', 'string', ['length' => 255]);
		$table->addColumn('password', 'string', ['length' => 255]);
		$table->addColumn('created_at', 'datetime');
		$table->addColumn('updated_at', 'datetime');

		# add table
		$sqlQuery = $schema->toSql($this->connection->getDatabasePlatform());
		list($sqlQuery) = $sqlQuery;

		# execute query
		$this->connection->executeQuery($sqlQuery);
	}

	public function down(Schema $schema): void {
		$schema->dropTable('user');
	}
}

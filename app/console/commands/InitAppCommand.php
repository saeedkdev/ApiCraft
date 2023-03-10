<?php

namespace Console\Commands;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use App\Database\DatabaseConnection;

#[AsCommand(
    name: 'app:init',
    description: 'Initialize the application',
)]

class InitAppCommand extends Command {

    protected function execute(InputInterface $input, OutputInterface $output) : int {

        $connection = DatabaseConnection::getConnection();

        $schema = DatabaseConnection::getSchema();

        # if schema has any tables, then the app is already initialized
        if (count($schema->getTables()) > 0) {
            $output->writeln('App is already initialized');
            return Command::SUCCESS;
        }

        $table = $schema->createTable('migrations');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('file_name', 'string', ['length' => 255]);
        $table->addColumn('created_at', 'datetime');
        $table->setPrimaryKey(['id']);


        # get the sql query of this table
        $sqlQuery = $schema->toSql($connection->getDatabasePlatform());
        list($sql) = $sqlQuery;
        if ($sql) {
            $output->writeln('Creating migrations table');
            $connection->executeStatement($sql);
        }

        $output->writeln('<info>Application initialized successfully</info>');

        return Command::SUCCESS;
    }

}

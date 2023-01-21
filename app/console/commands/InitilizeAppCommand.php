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

class InitilizeAppCommand extends Command {

    protected function execute(InputInterface $input, OutputInterface $output): int {

        $connection = DatabaseConnection::getConnection();

        $schema = DatabaseConnection::getSchema();

        $table = $schema->createTable('migrations');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('file_name', 'string', ['length' => 255]);
        $table->addColumn('created_at', 'datetime');
        $table->setPrimaryKey(['id']);

        $sqlQuery = $schema->toSql($connection->getDatabasePlatform());
        list($sql) = $sqlQuery;
        if ($sql) {
            $connection->executeStatement($sql);
        }

        $output->writeln('Application initialized');

        return Command::SUCCESS;
    }

}

<?php

namespace Console\Commands;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use App\Database\DatabaseConnection;

#[AsCommand(
    name: 'app:migrate',
	description: 'Run All Migrations',
)]

class RunMigrationCommand extends Command {
	
	protected function execute(InputInterface $input, OutputInterface $output) : int {
		$files = scandir(__DIR__ . '/../../migrations');
		$files = array_diff($files, array('.', '..'));
		$files = array_values($files);
		$files = array_reverse($files);

        $connection = DatabaseConnection::getConnection();
        $schema = DatabaseConnection::getSchema();
        $fromSchema = clone $schema;
        $logger = DatabaseConnection::getLogger();

        # if the migrations table does not exist, ask the user to run the init command
        try {
            $schema->getTable('migrations');
        } catch (\Exception $e) {
            $output->writeln('You need to initialize the app first. Run <info>php craft app:init</info> command.');
            return Command::FAILURE;
        }

        $fileNames = [];



		foreach ($files as $file) {
            // if file.php is already in the migrations table, skip it
            $qb = $connection->createQueryBuilder();
            $qb->select('*')
                ->from('migrations')
                ->where('file_name = :file_name')
                ->setParameter('file_name', $file);
            $result = $qb->execute()->fetchAssociative();
            if ($result) {
                continue;
            }
			// clases are return new class invoke up method
			$className = 'App\\Migrations\\' . str_replace('.php', '', $file);
			$migration = new $className($connection, $logger);
			$migration->up($schema);
            // execute schema up to here
            $fileNames[] = $file;
		}

        $qb = $connection->createQueryBuilder();
        foreach ($fileNames as $fileName) {
            $qb->insert('migrations')
                ->values([
                    'file_name' => '?',
                    'created_at' => '?'
                ])
                ->setParameter(0, $fileName)
                ->setParameter(1, date('Y-m-d H:i:s'));
            $qb->executeQuery();
        }


        $sql = $fromSchema->getMigrateToSql($schema, $connection->getDatabasePlatform());
        # create the new table
        if(empty($sql)){
            $output->writeln('<info>No new migrations found.</info>');
            return Command::SUCCESS;
        }
        foreach ($sql as $query) {
            $connection->executeQuery($query);
        }

		$output->writeln('<info>Migrations Run Successfully</info>');
		return Command::SUCCESS;
	}
}

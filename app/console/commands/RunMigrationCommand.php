<?php

namespace Console\Commands;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use	Doctrine\DBAL\Schema\Schema;

require_once __DIR__ . '/../../../config/config.php';

#[AsCommand(
name: 'app:migrate',
	description: 'Run All Migrations',
)]

class RunMigrationCommand extends Command {
	
	protected function execute(InputInterface $input, OutputInterface $output) : int 
	{
		$files = scandir(__DIR__ . '/../../migrations');
		$files = array_diff($files, array('.', '..'));
		$files = array_values($files);
		$files = array_reverse($files);

		$schema = new Schema();

		foreach ($files as $file) {
			// clases are return new class invoke up method
			$className = 'App\\Migrations\\' . str_replace('.php', '', $file);
			$migration = new $className();
			$migration->up($schema);
			$output->writeln('Migration ' . $file . ' ran successfully');
		}

		$output->writeln('Migrations Run Successfully');
		return Command::SUCCESS;
	}
}

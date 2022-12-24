<?php

namespace Console\Commands;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

require_once __DIR__ . '/../../../config/config.php';

#[AsCommand(
name: 'app:migrate',
	description: 'Run All Migrations',
)]

class RunMigrationCommand extends Command {
	protected function execute(InputInterface $input, OutputInterface $output) {
		$files = scandir(__DIR__ . '/../../migrations');
		$files = array_diff($files, array('.', '..'));
		$files = array_values($files);
		$files = array_reverse($files);

		foreach ($files as $file) {
			$className = 'App\\Migrations\\' . str_replace('.php', '', $file);
			$className::up();
		}

		$output->writeln('Migrations Run Successfully');
	}
}

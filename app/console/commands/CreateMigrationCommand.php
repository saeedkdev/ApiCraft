<?php

namespace Console\Commands;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

require_once __DIR__ . '/../../../config/config.php';

#[AsCommand(
	name: 'app:create-migration <table>',
	description: 'Creates a new migration',
)]

class CreateMigrationCommand extends Command {
	protected static $defaultDescription = 'Creates a new migration';

	protected function configure(): void
	{
		$this->setName('app:create-migration')
			->setDescription(self::$defaultDescription)
		;
		$this->addArgument('table');
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$table = $input->getArgument('table');
		$path = APP_ROOT .'/app/migrations/';
		$timestamp = date('Y_m_d_His');

		# migration file name
		$migrationFile = 'create_'.$table.'_table.php';

		# migration file path
		$migrationPath = $path . '/' . 'migration_'. $timestamp . '_' . $migrationFile;

		$tableClass = 'Migration_'.$timestamp . '_create_' . $table . '_table';

		# migration file content
        $migrationContent = <<<EOT
        <?php
        
        namespace App\Migrations;
        
        use Doctrine\DBAL\Schema\Schema;
        use Doctrine\Migrations\AbstractMigration;
        
        class $tableClass extends AbstractMigration {

            public function up(Schema \$schema): void
            {
                // this up() migration is auto-generated, please modify it to your needs
                \$table = \$schema->createTable('$table');
                \$table->addColumn('id', 'integer', ['autoincrement' => true]);
                \$table->addColumn('created_at', 'datetime');
                \$table->addColumn('updated_at', 'datetime');
                \$table->setPrimaryKey(['id']);
            }
        
            public function down(Schema \$schema): void
            {
                // this down() migration is auto-generated, please modify it to your needs
                \$schema->dropTable('$table');
            }
        }

        EOT;
            
        # create migration file
		file_put_contents($migrationPath, $migrationContent);

		$output->writeln('<info>Migration created successfully</info>');

		

		return Command::SUCCESS;
	}
}

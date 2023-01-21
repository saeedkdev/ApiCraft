<?php
    
namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

class Migration_2023_01_21_055950_create_users_table extends AbstractMigration {


    public function up(Schema $schema): void {
        // this up() migration is auto-generated, please modify it to your needs
        $table = $schema->createTable('users');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('name', 'string', ['length' => 255]);
        $table->addColumn('email', 'string', ['length' => 255]);
        $table->addColumn('password', 'string', ['length' => 255]);
        $table->addColumn('created_at', 'datetime');
        $table->addColumn('updated_at', 'datetime');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $schema->dropTable('users');
    }
}

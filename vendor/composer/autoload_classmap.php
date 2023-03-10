<?php

// autoload_classmap.php @generated by Composer

$vendorDir = dirname(__DIR__);
$baseDir = dirname($vendorDir);

return array(
    'App\\Database\\DatabaseConnection' => $baseDir . '/app/database/connection.php',
    'App\\Migrations\\Migration_2023_01_21_193710_create_users_table' => $baseDir . '/app/migrations/migration_2023_01_21_193710_create_users_table.php',
    'Composer\\InstalledVersions' => $vendorDir . '/composer/InstalledVersions.php',
    'Console\\Commands\\CreateMigrationCommand' => $baseDir . '/app/console/commands/CreateMigrationCommand.php',
    'Console\\Commands\\InitAppCommand' => $baseDir . '/app/console/commands/InitAppCommand.php',
    'Console\\Commands\\RunMigrationCommand' => $baseDir . '/app/console/commands/RunMigrationCommand.php',
    'Normalizer' => $vendorDir . '/symfony/polyfill-intl-normalizer/Resources/stubs/Normalizer.php',
);

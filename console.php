<?php

require 'vendor/autoload.php';
require 'index.php';

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputArgument;

$app = new Application('PHP CLI', '1.0.0');

$app->register('migrate')
    ->setDescription('Run the database migrations')
    ->setCode(function () {
        $configPath = __DIR__ . '/src/config/config.php';
        $config = require $configPath;

        $migrations = glob($config['paths']['migrations'] . '/*.php');
        foreach ($migrations as $migration) {
            $migrationInstance = require $migration;
            $migrationInstance->up();
        }

        echo "Migrations run successfully.\n";
    });

$app->register('migrate:down')
    ->setDescription('Run the database migrations')
    ->setCode(function () {
        $configPath = __DIR__ . '/src/config/config.php';
        $config = require $configPath;

        $migrations = glob($config['paths']['migrations'] . '/*.php');
        foreach ($migrations as $migration) {
            $migrationInstance = require $migration;
            $migrationInstance->down();
        }

        echo "Migrations run successfully.\n";
    });

$app->register('db:seed')
    ->setDescription('Run the database seeders')
    ->setCode(function () {
        $configPath = __DIR__ . '/src/config/config.php';
        $config = require $configPath;

        $seeders = glob($config['paths']['seeds'] . '/*.php');
        foreach ($seeders as $seeder) {
            require_once $seeder;
            $className = basename($seeder, '.php');
            $seederInstance = new $className();
            $seederInstance->run();
        }

        echo "Database seeded successfully.\n";
    });

$app->register('make:migration')
    ->setDescription('Create a new migration file')
    ->addArgument('name', InputArgument::REQUIRED, 'The name of the migration')
    ->setCode(function ($input) {
        $name = $input->getArgument('name');
        $parts = explode('_', $name);
        $operation = strtolower(array_shift($parts));
        $table = strtolower(array_shift($parts));

        foreach ($parts as $part) {
            if (!in_array($part, ['table', 'tables'], true)) {
                $table .= "_$part";
            }
        }

        $migrationTemplate = <<<EOT
<?php

use Illuminate\Database\Capsule\Manager as Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        Schema::schema()->{operation}('{tableName}', function (Blueprint \$table) {
            \$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::schema()->dropIfExists('{tableName}');
    }
};

EOT;

        $className = ucfirst(camel_case($name));
        $migrationContent = str_replace(
            ['{className}', '{operation}', '{tableName}'],
            [$className, $operation, $table],
            $migrationTemplate
        );

        $fileName = date('Y_m_d_His') . "_$name.php";
        $filePath = __DIR__ . '/database/migrations/' . $fileName;

        file_put_contents($filePath, $migrationContent);

        echo "Migration created successfully: $fileName\n";
    });

try {
    $app->run();
} catch (Exception $e) {
    echo "Internal Server Error";
}

function camel_case($string): array|string
{
    return str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $string)));
}

<?php

$container = require __DIR__ . '/../config/app.php';

$commands = [
    'serve' => function () {
        echo "Starting PHP built-in server at http://localhost:8000\n";
        passthru('php -S localhost:8000 -t public');
    },
    'migrate' => function () {
        $configPath = __DIR__ . '/../config/config.php';
        $config = require $configPath;

        $migrations = glob($config['paths']['migrations'] . '/*.php');
        foreach ($migrations as $migration) {
            $migrationInstance = require $migration;
            $migrationInstance->up();
        }

        echo "Migrations run successfully.\n";
    },
    'migrate:down' => function () {
        $configPath = __DIR__ . '/../config/config.php';
        $config = require $configPath;

        $migrations = glob($config['paths']['migrations'] . '/*.php');
        foreach ($migrations as $migration) {
            $migrationInstance = require $migration;
            $migrationInstance->down();
        }

        echo "Migrations rolled back successfully.\n";
    },
    'db:seed' => function () {
        $configPath = __DIR__ . '/../config/config.php';
        $config = require $configPath;

        $seeders = glob($config['paths']['seeds'] . '/*.php');
        foreach ($seeders as $seeder) {
            $seederInstance = require $seeder;
            $seederInstance->run();
        }

        echo "Database seeded successfully.\n";
    },
    'make:migration' => function () {
        global $argv;
        $name = $argv[2] ?? null;
        if (!$name) {
            echo "Error: Migration name is required.\n";
            return;
        }

        $parts = explode('_', $name);
        $operation = strtolower(array_shift($parts));

        if (in_array($operation, ['create', 'update'])) {
            $table = strtolower(implode('_', $parts));
            if (str_ends_with($table, '_table')) {
                $table = substr($table, 0, -6);
            }

            $migrationTemplate = <<<EOT
<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        Capsule::schema()->$operation('$table', function (Blueprint \$table) {
            \$table->increments('id');
            \$table->timestamps();
        });
    }

    public function down(): void
    {
        Capsule::schema()->dropIfExists('$table');
    }
};

EOT;

            $fileName = date('Y_m_d_His') . "_$name.php";
            $filePath = __DIR__ . '/../database/migrations/' . $fileName;

            file_put_contents($filePath, $migrationTemplate);

            echo "Migration created successfully: $fileName\n";
        } else {
            echo "Error: Migration name should start with 'create' or 'update'.\n";
        }
    },
];

$command = $argv[1] ?? null;

if ($command && isset($commands[$command])) {
    $commands[$command]();
} else {
    echo "Available commands:\n";
    foreach (array_keys($commands) as $cmd) {
        echo "  - $cmd\n";
    }
}

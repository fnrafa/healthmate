<?php

use Dotenv\Dotenv;
use Illuminate\Config\Repository;
use Illuminate\Container\Container;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Hashing\HashServiceProvider;
use Illuminate\Support\Facades\Facade;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/helpers.php';

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$container = new Container;
Container::setInstance($container);
$events = new Dispatcher($container);

$capsule = new Capsule;
$capsule->addConnection([
    'driver' => $_ENV['DB_CONNECTION'],
    'host' => $_ENV['DB_HOST'],
    'database' => $_ENV['DB_DATABASE'],
    'username' => $_ENV['DB_USERNAME'],
    'password' => $_ENV['DB_PASSWORD'],
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
]);

$capsule->setEventDispatcher($events);
$capsule->setAsGlobal();
$capsule->bootEloquent();

/** @var Application $container */
Facade::setFacadeApplication($container);

$container->singleton('config', function () {
    return new Repository([
        'hashing' => require __DIR__ . '/../config/hashing.php',
    ]);
});

$hashProvider = new HashServiceProvider($container);
$hashProvider->register();

return $container;

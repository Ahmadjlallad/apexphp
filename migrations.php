<?php

require_once __DIR__ . '/vendor/autoload.php';

use Apex\src\App;
use Apex\src\Database\Migration\ExecuteMigrations;
use Apex\src\Database\Migration\Schema\SchemaBuilder;

$dotenv = Dotenv\Dotenv::createImmutable((__DIR__));
$dotenv->load();
$config = [
    'db' => [
        'type' => $_ENV['DB_TYPE'],
        'host' => $_ENV['DB_HOST'],
        'port' => $_ENV['DB_PORT'],
        'name' => $_ENV['DB_NAME'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD'],
    ],
    'ROOT_DIR' => __DIR__
];


$app = new App($config);

(new ExecuteMigrations($app->db->pdo, new SchemaBuilder()))->applyMigration();
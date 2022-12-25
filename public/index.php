<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Apex\src\App;

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();
$config = [
    'db' => [
        'type' => $_ENV['DB_TYPE'],
        'host' => $_ENV['DB_HOST'],
        'port' => $_ENV['DB_PORT'],
        'name' => $_ENV['DB_NAME'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD'],
    ]
];


$app = new App($config);
include_once __DIR__ . '/../routes/web.php';
$app->run();
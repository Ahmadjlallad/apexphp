#!/usr/bin/env php
<?php
error_reporting(E_ALL);


use Apex\src\Console\Application;
use Apex\src\Console\Migration\Create;
use Apex\src\Console\Migration\Migration;
use Apex\src\Console\Migration\Up;
use Apex\src\Console\Model\Model;
use Apex\src\Console\Serve;
use Apex\src\Database\Database;

require_once __DIR__ . '/vendor/autoload.php';


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

$application = new Application();

$application->add(new Serve('serve'));
$application->add(new Model((new Database($config['db']))->pdo));
$application->add(new Create());
$application->add(new Up());


try {
    $application->run();
} catch (Exception $e) {
    dd("running $e");
}
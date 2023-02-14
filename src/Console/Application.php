<?php

namespace Apex\src\Console;

use Apex\src\App;
use Dotenv\Dotenv;

class Application extends \Symfony\Component\Console\Application
{
    public function __construct(string $name = 'UNKNOWN', string $version = 'UNKNOWN')
    {
        $dotenv = Dotenv::createImmutable($_SERVER['PWD']);
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
            'ROOT_DIR' => $_SERVER['PWD'],
            'haveSession' => false
        ];
        new App($config);
        parent::__construct($name, $version);
    }

    public function serve($port = '8080')
    {
        return shell_exec('cd public && php -S localhost:' . $port);
    }
}
<?php

namespace Apex\src\Console;

class Application extends \Symfony\Component\Console\Application
{
    public function serve($port = '8080')
    {
        return shell_exec('cd public && php -S localhost:' . $port);
    }
}
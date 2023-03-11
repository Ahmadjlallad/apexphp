<?php

namespace Apex\src\Console\Migration;

use Apex\src\Database\Migration\ExecuteMigrations;
use Apex\src\Database\Migration\Schema\SchemaBuilder;
use Dotenv\Dotenv;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Apex\src\App;
class Up extends Migration
{


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        (new ExecuteMigrations(app()->db->pdo, new SchemaBuilder()))->applyMigration();
        return Command::SUCCESS;
    }
}
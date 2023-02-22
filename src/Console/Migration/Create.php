<?php

namespace Apex\src\Console\Migration;

use Carbon\Carbon;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Create extends Migration
{
    protected function configure()
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'migration file name');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $migrationName = $input->getArgument('name');
        if (preg_match('/[\'^£$%&*()}{@#~?><,|=+¬-]/', $migrationName)) {
            $output->writeln('<error>Migration file name shouldn\'t contain spacial characters</error>');
            return Command::FAILURE;
        }
        $migrationName = 'm' . Carbon::now()->unix() . '_' . $migrationName;
        $migrationTemplate = str_replace('@className', $migrationName, file_get_contents(dirname(__DIR__) . '/Migration/migration.example'));
        if (!file_put_contents($_SERVER['PWD'] . '/migrations/' . $migrationName . '.php', $migrationTemplate)) {
            $output->writeln('<error>Couldn\'t create a migration file</error>');
            return Command::FAILURE;
        }
        $output->writeln("<info>Migration file created successfully $migrationName</info>");
        return Command::SUCCESS;
    }
}
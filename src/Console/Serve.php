<?php

namespace Apex\src\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Serve extends Command
{
    protected static $defaultDescription = 'Open php server';
    private string $port = '8080';

    protected function configure(): void
    {
        $this->addOption('port', '-p', InputArgument::OPTIONAL, 'served at Port? default 8080');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $port = $input->getOption('port') ?? $this->port;
        exec('cd public && php -S localhost:' . $port);
        return Command::SUCCESS;
    }
}
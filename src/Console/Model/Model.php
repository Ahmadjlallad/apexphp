<?php

namespace Apex\src\Console\Model;

use Apex\src\Database\Migration\Schema\Definition\MysqlTypes;
use Apex\src\Model\TableSchema;
use Exception;
use PDO;
use ReflectionEnum;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\VarDumper\VarDumper;


#[AsCommand(
    name: 'model:create',
    description: 'Creates a new model.',
    hidden: false
)]
class Model extends Command
{
    use TableSchema;

    public function __construct(private readonly PDO $PDO)
    {
        parent::__construct('model:create');
    }

    protected function configure()
    {
        $this
            ->addArgument('name', InputArgument::REQUIRED, 'name for the model')
            ->addOption('table', '-t', InputOption::VALUE_OPTIONAL, 'table name');
    }

    /**
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $file = file_get_contents(__DIR__ . "/model.example");
        $modelName = ucfirst($input->getArgument('name'));
        $file = str_replace('@modelName', $modelName, $file);
        $docBlock = $this->getTableSchemaDocBlock($this->PDO, $input->getOption('table') ?? $modelName);
        $file = str_replace('@docBlock', $docBlock, $file);
        $fileName = dirname(__DIR__, 3) . '/models/' . $modelName . '.php';
        if (file_exists($fileName)) {
            $output->writeln('<info>[INFO] Model already exists</info>');
            return Command::SUCCESS;
        }
        file_put_contents($fileName, $file);
        $output->writeln('<info>[INFO] Model created successfully</info>');
        return Command::SUCCESS;
    }
}
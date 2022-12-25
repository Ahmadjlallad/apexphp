<?php

namespace Apex\src\Database\Migration;

use Apex\src\Database\Migration\Schema\Builder;
use PDO;
use Symfony\Component\VarDumper\VarDumper;

abstract class Migration
{
    private array $savedStatements = [];

    public function __construct(public PDO $pdo, private readonly Builder $schemaBuilder)
    {
    }

    abstract public function up(): void;

    abstract public function down(): void;

    public function save(): void
    {
        $statement = implode(";", $this->savedStatements);
        VarDumper::dump($statement);
        $this->pdo->exec($statement);
    }

    /**
     * create table
     * @param string $table
     * @param array $columns array{string: string}
     * @return Void
     */
    public function createTable(string $table, array $columns): void
    {
        $this->savedStatements[] = $this->schemaBuilder->create($table, $columns);
    }

    /**
     * @param string $table
     * @param array $columns array{string: string}
     * @return void
     */
    public function createTableIfNotExiest(string $table, array $columns): void
    {
        $this->savedStatements[] = $this->schemaBuilder->create($table, $columns, true);
    }

    /**
     * @param $foreignKeyConfig array{table: string, fTable: string, fColumn: string, pointsOn: string }
     * @return void
     */
    public function createForeginKey(array $foreignKeyConfig): void
    {
        $this->savedStatements[] = $this->schemaBuilder->addForeignKey($foreignKeyConfig);
    }
}
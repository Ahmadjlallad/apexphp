<?php

namespace Apex\src\Database\Migration;

use Apex\src\Database\Migration\Schema\Relations;
use PDO;

abstract class Migration
{
    private $saveStatements = [];

    public function __construct(public PDO $pdo)
    {
    }

    abstract public function up(): void;

    abstract public function down(): void;

    /**
     * create table
     * @param string $table
     * @param array $columns
     * @return string
     */
    public function create(string $table, array $columns): string
    {
        $i = 0;
        $data = '';
        foreach ($columns as $columnName => $columnProprieties) {
            $data .= sprintf("\t%s %s%s", $columnName, $columnProprieties, $i < count($columns) - 1 ? ",\n" : "\n");
            $i++;
        }
        $r = "CREATE TABLE $table \n(\n$data)" . PHP_EOL;
        return $r;
    }
}
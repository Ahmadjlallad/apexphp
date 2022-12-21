<?php

namespace Apex\src\Database\Migration;

use Apex\src\App;
use Apex\src\Database\Database;
use PDO;
use Symfony\Component\VarDumper\VarDumper;

abstract class Migration
{
    public function __construct(public PDO $pdo)
    {
    }
    abstract public function up(): void;
    abstract public function down(): void;

    /**
     * create table
     * @return void
     *
     */
    public function create(string $table,array $columns)
    {

    }
}
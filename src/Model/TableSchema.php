<?php

namespace Apex\src\Model;

use Apex\src\Database\Migration\Schema\Definition\MysqlTypes;
use Apex\src\Database\Query\ColumnBlueprint;
use Exception;
use PDO;
use PDOException;
use ReflectionEnum;
use Symfony\Component\VarDumper\VarDumper;

trait TableSchema
{

    private ?string $primaryKey = null;

    public function getTablePrimaryKey()
    {
//        show index from users where Key_name = 'PRIMARY';
    }

    /**
     * @throws Exception
     */
    private function getTableSchemaDocBlock(PDO $PDO, string $tableSchema): string
    {
        $tableSchema = $this->getColumns($PDO, $tableSchema);
        $docBlock = "/**\n";
        foreach ($tableSchema as $columnBlueprint) {
            $type = strtoupper($columnBlueprint->Type);
            $reflection = new ReflectionEnum(MysqlTypes::class);
            VarDumper::dump($reflection->hasCase($type));
            VarDumper::dump($type);
            if ($reflection->hasCase(strtoupper($type))) {
                $type = MysqlTypes::typeName($type);
                $type = $type->type();
            } else {
                $type = MysqlTypes::STRING->type();
            }
            $docBlock .= " * @property $type $columnBlueprint->Field\n";
        }
        $docBlock .= ' */';
        return $docBlock;
    }

    /**
     * @param PDO $PDOConnection
     * @param string $table
     * @return array<ColumnBlueprint>
     */
    private function getColumns(PDO $PDOConnection, string $table): array
    {
        try {
            $columnsStatement = $PDOConnection->query('DESCRIBE ' . $this->getTable($table));
            $columnsStatement->execute();
            return $columnsStatement->fetchAll(PDO::FETCH_CLASS);
        } catch (PDOException $e) {
            dd($e);
        }
    }

    /**
     * @param string $table
     * @return string
     */
    private function getTable(string $table): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $table));
    }
}
<?php

namespace Apex\src\Database\Migration\Schema;

use Apex\src\Database\Migration\Schema\Definition\ForeignIdColumnDefinition;

class SchemaBuilder implements Builder
{
    /**
     * create table
     * @param string $table
     * @param array $columns
     * @param bool $checkIfNotExist
     * @return string
     */
    public function create(string $table, array $columns, bool $checkIfNotExist = false): string
    {
        $i = 0;
        $data = '';
        foreach ($columns as $columnName => $columnProprieties) {
            $data .= sprintf("  %s %s%s", $columnName, $columnProprieties, $i < count($columns) - 1 ? "," : "");
            $i++;
        }
        return sprintf("CREATE %s $table ($data)", $checkIfNotExist ? 'TABLE IF NOT EXISTS' : 'TABLE');
    }

    /**
     * @param $config array{ table: string, fTable: string, fColumn: string, pointsOn: string }
     * @return string
     */
    public function addForeignKey(array $config): string
    {
        return (new ForeignIdColumnDefinition())->addForeignKey($config);
    }
}
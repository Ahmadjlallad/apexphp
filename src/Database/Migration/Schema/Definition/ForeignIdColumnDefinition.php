<?php

namespace Apex\src\Database\Migration\Schema\Definition;
//TODO FIX FIRST
use Apex\src\Database\Migration\MigrationsTrait\Sanitize;

class ForeignIdColumnDefinition
{
    use Sanitize;

    private const FOREIGN_KEY = 'FOREIGN KEY';
    private const REFERENCES = 'REFERENCES';
    private const ALTER = 'ALTER';
    private const TABLE = 'TABLE';
    private const ADD = 'ADD';
    private const CONSTRAINT = 'CONSTRAINT';
    private string $table;
    private string $statement;

    public function __construct()
    {
        $this->statement = sprintf('%s %s :table %s %s :foreignKeyName %s (:columnName) %s :fTableName(:fColumnName)', static::ALTER, static::TABLE, static::ADD, static::CONSTRAINT, static::FOREIGN_KEY, static::REFERENCES);
    }

    /**
     * @param $config array{table: string, fTable: string, fColumn: string, pointsOn: string }
     * @return string
     */
    public function addForeignKey(array $config): string
    {
        $this->foreign($config['table'])->references($config['pointsOn'])->on($config['fTable'], $config['fColumn']);
        return $this->statement;
    }

    /**
     * @param string $pointAtFTable
     * @param string $pointAtFColumn
     * @param string|null $relationName
     * @return void
     */
    private function on(string $pointAtFTable, string $pointAtFColumn, string|null $relationName = null): void
    {
        $this->statement = $this->bindValue(':fTableName', $pointAtFTable);
        $this->statement = $this->bindValue(':fColumnName', $pointAtFColumn);
        $relationName ??= 'fk_' . $this->table . '_' . $pointAtFTable . '_' . $pointAtFColumn;
        $this->statement = $this->bindValue(':foreignKeyName', $relationName);
    }

    private function bindValue(string $bindToAtt, mixed $value): string|array
    {
        return str_replace($bindToAtt, $this->sanitizeString($value), $this->statement);
    }

    /**
     * @param string $pointToColumn
     * @return $this
     */
    private function references(string $pointToColumn): static
    {
        $this->statement = $this->bindValue(':columnName', $pointToColumn);
        return $this;
    }

    /**
     * @param string $table
     * @return $this
     */
    private function foreign(string $table): static
    {
        $this->table = $table;
        $this->statement = $this->bindValue(':table', $table);
        return $this;
    }
}
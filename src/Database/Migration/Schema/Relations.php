<?php

namespace Apex\src\Database\Migration\Schema;
//TODO FIX FIRST
class Relations
{
    private const FOREIGN_KEY = 'FOREIGN KEY';
    private const REFERENCES = 'REFERENCES';
    private const ALTER = 'ALTER';
    private const TABLE = 'TABLE';
    private const ADD = 'ADD';
    private string $statement = '';

    /**
     * "TableName ADD foreign key (key)";
     * @param string $table
     * @return $this
     */
    private function foreign(string $table): static
    {
        $this->statement = sprintf(' %s %s %s ', $table, static::ADD, static::FOREIGN_KEY);
        return $this;
    }

    /**
     *  REFERENCES test(a);
     * @param string $pointOnTable
     * @param string $pointToColumn
     * @return $this
     */
    private function references(string $pointOnTable, string $pointToColumn): static
    {
        $this->statement = static::REFERENCES . $pointOnTable . "($pointToColumn)";
        return $this;
    }

    /**
     * @param string $pointAtColumn
     * @return $this
     */
    private function on(string $pointAtColumn): static
    {
        $this->statement = "($pointAtColumn)";
        return $this;
    }

    /**
     * @param $option array{table: string, fTable: string, fColumn: string, pointsOn: string }
     * @return string
     */
    public function addForgeKey(array $option): string
    {
        $this->foreign($option['table'])->references($option['fTable'], $option['fColumn'])->on($option['pointsOn']);
        return sprintf('%s %s %s', static::ALTER, static::TABLE, $this->statement);
    }
}
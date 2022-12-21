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
    private $table = '';

    /**
     * "TableName ADD foreign key (key)";
     * @param string $table
     * @return $this
     */
    public function foreign(string $table): static
    {
        $this->table = $table;
        $this->statement = sprintf(' %s %s %s ', $table, static::ADD, static::FOREIGN_KEY);
        return $this;
    }

    /**
     *  REFERENCES test(a);
     * @param string $pointOn
     * @return $this
     */
    public function references(string $pointOn): static
    {
        $this->statement = static::REFERENCES . " $pointOn ";
        return $this;
    }

    /**
     * @param string $pointAtTable
     * @return $this
     */
    public function on(string $pointAtTable): static
    {
        $this->statement = "($pointAtTable)";
        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return sprintf();
    }
}
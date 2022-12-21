<?php

namespace Apex\src\Database\Migration\Schema;

class Relations
{
    protected const FOREIGN_KEY = 'FOREIGN KEY';
    protected const REFERENCES = 'REFERENCES';
    public string $statement = '';

    /**
     * @param string $columnName
     * @return $this
     */
    public function foreign(string $columnName): static
    {
        $this->statement = static::FOREIGN_KEY . " $columnName ";
        return $this;
    }

    /**
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
        return $this->statement;
    }
}
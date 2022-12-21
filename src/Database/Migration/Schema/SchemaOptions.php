<?php

namespace Apex\src\Database\Migration\Schema;

class SchemaOptions
{
    private const NOT_NULL = 'NOT NULL';
    private const UNIQUE = 'UNIQUE';
    private const AFTER = 'AFTER';
    private const DEFAULT = 'DEFAULT';

    public function __construct(private string $statement)
    {
    }

    /**
     * @return $this
     */
    public function notNull(): static
    {
        $this->statement .= static::NOT_NULL . ' ';
        return $this;
    }

    /**
     * @return $this
     */
    public function unique(): static
    {
        $this->statement .= static::UNIQUE . ' ';
        return $this;
    }

    /**
     * @param string $column
     * @return $this
     */
    public function after(string $column): static
    {
        $this->statement .= static::AFTER . " $column ";
        return $this;
    }

    /**
     * @return $this
     */
    public function default(): static
    {
        $this->statement .= static::DEFAULT . ' ';
        return $this;
    }

    public function __toString(): string
    {
        return $this->statement;
    }
}
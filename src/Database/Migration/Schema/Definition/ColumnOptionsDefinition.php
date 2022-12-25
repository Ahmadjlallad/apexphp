<?php

namespace Apex\src\Database\Migration\Schema\Definition;

use Apex\src\Database\Migration\MigrationsTrait\Sanitize;

class ColumnOptionsDefinition
{
    use Sanitize;

    private const NOT_NULL = 'NOT NULL';
    private const UNIQUE = 'UNIQUE';
    private const AFTER = 'AFTER';
    private const DEFAULT = 'DEFAULT';
    private const ON_UPDATE = 'ON UPDATE';

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
        $column = $this->sanitizeString($column);
        $this->statement .= static::AFTER . " $column ";
        return $this;
    }

    /**
     * @param string $default
     * @param DefaultType $type enum builtin|string|int
     * @return $this
     */
    public function default(string $default, DefaultType $type = DefaultType::STRING): static
    {
        $default = $this->sanitizeString($default);
        $this->statement .= sprintf("%s %s ", static::DEFAULT, $type === DefaultType::STRING ? "$default" : $default);
        return $this;
    }

    public function onUpdate(string $action): static
    {
        $this->statement .= sprintf('%s %s ', static::ON_UPDATE, $this->sanitizeString($action));
        return $this;
    }

    public function __toString(): string
    {
        return $this->statement;
    }
}
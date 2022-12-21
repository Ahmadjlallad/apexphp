<?php

namespace Apex\src\Database\Migration;

/**
 *  Schema Builder for mysql
 */
class Schema
{
    public const INTEGER = 'INT';
    public const TINYINT = 'TINYINT';
    public const BIGINT = 'BIGINT';
    public const TEXT = 'TEXT';
    public const TIMESTAMPS = 'TIMESTAMPS';
    public const DATETIME = 'DATETIME';
    public const PRIMARY_KEY = 'PRIMARY KEY';
    public const AUTO_INCREMENT = 'AUTO INCREMENT';
    public const NOT_NULL = 'NOT NULL';
    public const UNIQUE = 'UNIQUE';
    public const DEFAULT = 'DEFAULT';
    public const AFTER = 'AFTER';
    /**
     * @var string
     */
    public string $statement = '';

    /**
     * @return $this
     */
    public function timestaps(): static
    {
        $this->statement .= static::TIMESTAMPS . ' ';
        return $this;
    }

    /**
     * @return $this
     */
    public function datetime(): static
    {
        $this->statement .= static::DATETIME . ' ';
        return $this;
    }

    /**
     * @return $this
     */
    public function text(): static
    {
        $this->statement .= static::TEXT . ' ';
        return $this;
    }

    /**
     * @return $this
     */
    public function tinyint(): static
    {
        $this->statement .= static::TINYINT . ' ';
        return $this;
    }

    /**
     * @return $this
     */
    public function integer(): static
    {
        $this->statement .= static::INTEGER . ' ';
        return $this;
    }

    /**
     * decimal db
     * @param int $pres
     * @param int $scale
     * @return Schema
     */
    public function decimal(int $pres, int $scale): static
    {
        $this->statement .= "DECIMAL($pres, $scale) ";
        return $this;
    }

    /**
     * varchar
     * @param string $length
     * @return string
     */
    public function string(string $length): string
    {
        return "VARCHAR($length)";
    }

    /**
     * enums
     * @param array $enumValues
     * @return Schema
     */
    public function enum(array $enumValues): static
    {
        $enumValues = implode(', ', $enumValues);
        $this->statement .= "ENUM($enumValues) ";
        return $this;
    }

    /**
     * @param bool $autoIncrement
     * @return $this
     */
    public function primaryKey(bool $autoIncrement = true): static
    {
        $this->statement .= sprintf('%s %s %s', static::BIGINT, static::PRIMARY_KEY . ' ', $autoIncrement ? static::AUTO_INCREMENT : '');
        return $this;
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

    public function foreign(string $columnName)
    {
    }

    public function references(string $pointOn)
    {
    }

    public function on(string $pointAtTable)
    {
    }
}
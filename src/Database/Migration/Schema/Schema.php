<?php

namespace Apex\src\Database\Migration\Schema;

/**
 *  Schema Builder for mysql
 */
class Schema
{
    protected const INTEGER = 'INT';
    protected const TINYINT = 'TINYINT';
    protected const BIGINT = 'BIGINT';
    protected const TEXT = 'TEXT';
    protected const TIMESTAMPS = 'TIMESTAMPS';
    protected const DATETIME = 'DATETIME';
    protected const PRIMARY_KEY = 'PRIMARY KEY';
    protected const AUTO_INCREMENT = 'AUTO_INCREMENT';
    protected const NOT_NULL = 'NOT NULL';
    protected const UNIQUE = 'UNIQUE';
    protected const DEFAULT = 'DEFAULT';
    protected const AFTER = 'AFTER';
    protected const VARCHAR = 'VARCHAR';
    /**
     * @var string
     */
    public string $statement = '';

    /**
     * @return $this
     */
    public function timestamps(): static
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
     * @param int $length
     * @return static
     */
    public function string(int $length = 255): static
    {
        $this->statement .= static::VARCHAR . "($length) ";
        return $this;
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
        $this->statement .= sprintf('%s %s %s', static::BIGINT, static::PRIMARY_KEY, $autoIncrement ? static::AUTO_INCREMENT.' ' : '');
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

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->statement;
    }
}
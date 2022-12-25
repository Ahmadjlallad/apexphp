<?php

namespace Apex\src\Database\Migration\Schema\Definition;

/**
 *  Schema Builder for mysql
 * @static primaryKey()
 */
class ColumnDefinition
{
    public const INTEGER = 'INT';
    public const TINYINT = 'TINYINT';
    public const BIGINT = 'BIGINT';
    public const TEXT = 'TEXT';
    public const TIMESTAMP = 'TIMESTAMP';
    public const DATETIME = 'DATETIME';
    public const PRIMARY_KEY = 'PRIMARY KEY';
    public const AUTO_INCREMENT = 'AUTO_INCREMENT';
    public const VARCHAR = 'VARCHAR';
    public const CURRENT_TIMESTAMP = 'CURRENT_TIMESTAMP';

    /**
     * @var string
     */
    private string $statement = '';

    /**
     * @return ColumnOptionsDefinition
     */
    public function timestamps(): ColumnOptionsDefinition
    {
        $this->statement .= static::TIMESTAMP . ' ';
        return new ColumnOptionsDefinition($this->statement);
    }

    /**
     * @return ColumnOptionsDefinition
     */
    public function datetime(): ColumnOptionsDefinition
    {
        $this->statement .= static::DATETIME . ' ';
        return new ColumnOptionsDefinition($this->statement);

    }

    /**
     * @return ColumnOptionsDefinition
     */
    public function text(): ColumnOptionsDefinition
    {
        $this->statement .= static::TEXT . ' ';
        return new ColumnOptionsDefinition($this->statement);

    }

    /**
     * @return ColumnOptionsDefinition
     */
    public function tinyint(): ColumnOptionsDefinition
    {
        $this->statement .= static::TINYINT . ' ';
        return new ColumnOptionsDefinition($this->statement);

    }

    /**
     * @return ColumnOptionsDefinition
     */
    public function integer(): ColumnOptionsDefinition
    {
        $this->statement .= static::INTEGER . ' ';
        return new ColumnOptionsDefinition($this->statement);

    }

    /**
     * decimal db
     * @param int $pres
     * @param int $scale
     * @return ColumnOptionsDefinition
     */
    public function decimal(int $pres, int $scale): ColumnOptionsDefinition
    {
        $this->statement .= "DECIMAL($pres, $scale) ";
        return new ColumnOptionsDefinition($this->statement);
    }

    /**
     * varchar
     * @param int $length
     * @return ColumnOptionsDefinition
     */
    public function string(int $length = 255): ColumnOptionsDefinition
    {
        $this->statement .= static::VARCHAR . "($length) ";
        return new ColumnOptionsDefinition($this->statement);
    }

    /**
     * enums
     * @param array $enumValues
     * @return ColumnOptionsDefinition
     */
    public function enum(array $enumValues): ColumnOptionsDefinition
    {
        $enumValues = implode(', ', $enumValues);
        $this->statement .= "ENUM($enumValues) ";
        return new ColumnOptionsDefinition($this->statement);

    }

    /**
     * @param bool $autoIncrement
     * @return ColumnOptionsDefinition
     */
    public function primaryKey(bool $autoIncrement = true): ColumnOptionsDefinition
    {
        $this->statement .= sprintf('%s %s %s', static::BIGINT, static::PRIMARY_KEY, $autoIncrement ? static::AUTO_INCREMENT . ' ' : '');
        return new ColumnOptionsDefinition($this->statement);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->statement;
    }
}
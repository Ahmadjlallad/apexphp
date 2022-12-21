<?php

namespace Apex\src\Database\Migration\Schema;

/**
 *  Schema Builder for mysql
 */
class Schema
{
    private const INTEGER = 'INT';
    private const TINYINT = 'TINYINT';
    private const BIGINT = 'BIGINT';
    private const TEXT = 'TEXT';
    private const TIMESTAMPS = 'TIMESTAMPS';
    private const DATETIME = 'DATETIME';
    private const PRIMARY_KEY = 'PRIMARY KEY';
    private const AUTO_INCREMENT = 'AUTO_INCREMENT';
    private const VARCHAR = 'VARCHAR';
    /**
     * @var string
     */
    public string $statement = '';

    /**
     * @return SchemaOptions
     */
    public function timestamps(): SchemaOptions
    {
        $this->statement .= static::TIMESTAMPS . ' ';
        return new SchemaOptions($this->statement);

    }

    /**
     * @return SchemaOptions
     */
    public function datetime(): SchemaOptions
    {
        $this->statement .= static::DATETIME . ' ';
        return new SchemaOptions($this->statement);

    }

    /**
     * @return SchemaOptions
     */
    public function text(): SchemaOptions
    {
        $this->statement .= static::TEXT . ' ';
        return new SchemaOptions($this->statement);

    }

    /**
     * @return SchemaOptions
     */
    public function tinyint(): SchemaOptions
    {
        $this->statement .= static::TINYINT . ' ';
        return new SchemaOptions($this->statement);

    }

    /**
     * @return SchemaOptions
     */
    public function integer(): SchemaOptions
    {
        $this->statement .= static::INTEGER . ' ';
        return new SchemaOptions($this->statement);

    }

    /**
     * decimal db
     * @param int $pres
     * @param int $scale
     * @return SchemaOptions
     */
    public function decimal(int $pres, int $scale): SchemaOptions
    {
        $this->statement .= "DECIMAL($pres, $scale) ";
        return new SchemaOptions($this->statement);
    }

    /**
     * varchar
     * @param int $length
     * @return SchemaOptions
     */
    public function string(int $length = 255): SchemaOptions
    {
        $this->statement .= static::VARCHAR . "($length) ";
        return new SchemaOptions($this->statement);

    }

    /**
     * enums
     * @param array $enumValues
     * @return SchemaOptions
     */
    public function enum(array $enumValues): SchemaOptions
    {
        $enumValues = implode(', ', $enumValues);
        $this->statement .= "ENUM($enumValues) ";
        return new SchemaOptions($this->statement);

    }

    /**
     * @param bool $autoIncrement
     * @return SchemaOptions
     */
    public function primaryKey(bool $autoIncrement = true): SchemaOptions
    {
        $this->statement .= sprintf('%s %s %s', static::BIGINT, static::PRIMARY_KEY, $autoIncrement ? static::AUTO_INCREMENT . ' ' : '');
        return new SchemaOptions($this->statement);

    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->statement;
    }
}
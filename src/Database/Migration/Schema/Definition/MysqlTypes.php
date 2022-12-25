<?php

namespace Apex\src\Database\Migration\Schema\Definition;

use Carbon\Carbon;
use Exception;

enum MysqlTypes
{
    case TINYINT;
    case LONG;
    case FLOAT;
    case DOUBLE;
    case TIMESTAMP;
    case DATE;
    case TIME;
    case DATETIME;
    case YEAR;
    case ENUM;
    case SET;
    case TINY_BLOB;
    case MEDIUM_BLOB;
    case LONG_BLOB;
    case BLOB;
    case VAR_STRING;
    case STRING;
    case NULL;
    case INTERVAL;
    case GEOMETRY;
    case BIGINT;

    /**
     * @throws Exception
     */
    public function type(): string
    {
        return match ($this) {
            self::STRING, self::VAR_STRING, self::ENUM, self::TINY_BLOB, self::LONG_BLOB,
            self::BLOB, self::MEDIUM_BLOB => 'string',
            self::TINYINT, self::LONG, self::BIGINT => 'int',
            self::FLOAT, self::DOUBLE => 'float',
            self::DATE, self::TIMESTAMP, self::TIME, self::YEAR, self::DATETIME => '\\'.Carbon::class.'|null',
            self::NULL => 'null',
            self::SET, self::INTERVAL, self::GEOMETRY => throw new Exception('To be implemented'),
        };
    }
    public static function typeName(string $name){
        return constant("self::$name");
    }
}

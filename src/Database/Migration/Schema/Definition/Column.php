<?php

namespace Apex\src\Database\Migration\Schema\Definition;
class Column
{
    public static function add(): ColumnDefinition
    {
        return new ColumnDefinition();
    }
}
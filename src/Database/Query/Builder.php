<?php

namespace Apex\src\Database\Query;

class Builder
{
    public $bindings = [
        'select' => [],
        'from' => [],
        'join' => [],
        'where' => [],
        'groupBy' => [],
        'having' => [],
        'order' => [],
        'union' => [],
        'unionOrder' => [],
    ];

    public function get(array $columns): array
    {
        return $columns;
    }
}
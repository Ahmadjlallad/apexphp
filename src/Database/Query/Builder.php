<?php

namespace Apex\src\Database\Query;

use Apex\src\App;
use Apex\src\Model\Model;

class Builder
{
    public function __construct(protected Model $model)
    {
        $this->model->setConnection(App::getInstance()->db->pdo);
    }

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

    public function prepareSqlInsert(array $values): string
    {
        $table = $this->model->getTable();
        $columns = $this->columnize($values);
        $binding = implode(', ', array_map(fn() => " ? ", $values));
        return "INSERT INTO $table($columns) value ($binding)";
    }

    public function columnize(array $values): string
    {
        return implode(', ', array_map(fn($value) => "`$value`", array_keys($values)));
    }
}
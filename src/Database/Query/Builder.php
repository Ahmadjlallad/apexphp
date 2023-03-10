<?php
declare(strict_types=1);

namespace Apex\src\Database\Query;

use Apex\src\App;
use Apex\src\Helpers\Arrays;
use Apex\src\Model\Model;

class Builder
{
    use Arrays;

    public array $bindings = [
        'select' => [],
        'from' => [],
        'join' => [],
        'where' => [],
        'groupBy' => [],
        'order' => [],
    ];
    public int|null $limit = null;
    public array $where = [];

    public function __construct(protected Model $model)
    {
        $this->model->setConnection(app()->db->pdo);
    }

    public function get(array $columns): array
    {
        return $columns;
    }

    public function prepareWhere(callable|array|string $column, ?string $conditions = null, mixed $value = null, string $boolean = 'AND'): void
    {
        if (!empty($conditions) && !empty($value)) {
            $this->where(func_get_args());
        } elseif (is_array($column)) {
            $this->constructWhereFromArray($column);
        }
    }

    /**
     * @param array{column: string, operator: string , value: string, boolean: string} $values
     * @return void
     */
    private function where(array $values): void
    {
        $this->bindProperty($values[2], 'where');
        $this->where[] = ['column' => $values[0], 'operator' => $values[1], 'value' => $values[2], 'boolean' => $values[3] ?? 'and'];
    }

    /**
     * @param string $value
     * @param mixed $set
     * @return void
     */
    private function bindProperty(mixed $value, string $set): void
    {
        if (is_array($value)) {
            foreach ($value as $item) {
                $this->bindProperty($item, $set);
            }
        } else $this->bindings[$set][] = $value;
    }

    private function constructWhereFromArray(string|array $query): void
    {
        // @todo refactor this
        $isFirstKeyString = is_string(array_key_first($query)) || is_string($query[0]);
        if (count($query) <= 3 && $isFirstKeyString) {
            $this->constructWhere($query);
        } else {
            foreach ($query as $items) {
                $this->constructWhere($items);
            }
        }
    }

    private function constructWhere(array $query): void
    {
        if (!$this->isAssoc($query)) {
            $this->where($query);
            return;
        }
        $key = array_key_first($query);
        $values = $query[$key];
        if (is_array($values)) {
            $this->whereIn($key, $values);
            return;
        }
        $this->whereEqual($key, $values);
    }

    public function whereIn($column, $values): void
    {
        $this->where([$column, 'IN', $values]);
    }

    public function whereEqual($column, $values): void
    {
        $this->where([$column, '=', $values]);
    }

    public function prepareSelect(array|string $columns): void
    {
        $this->bindings['select'] = is_array($columns) ? $columns : [$columns];
        $this->bindings['from'][] = $this->model->getTable();
    }

    public function prepareGetStatement(): string
    {
        if (empty($this->bindings['from'])) {
            $this->bindings['from'][] = $this->model->getTable();
        }
        if (empty($this->bindings['select'])) {
            $this->bindings['select'][] = '*';
        }
        $binding = $this->bindings;
        $statement = 'select' . ' ' . implode(',', $binding['select']);
        $statement .= ' from ' . implode($binding['from']);
        if (!empty($binding['where'])) {
            $statement .= ' where';
        }
        foreach ($this->where as $i => $where) {
            if ($i > 0) {
                $statement .= " " . $where['boolean'];
            }
            if ($where['operator'] === 'IN' || $where['operator'] === 'in') {
                $statement .= " " . $where['column'] . " " . $where['operator'] . ' ' . '(' . implode(',', array_map(fn() => "?", $where['value'])) . ')';
            } else {
                $statement .= " " . $where['column'] . ' ' . $where['operator'] . ' ' . '?';
            }
        }
        if ($this->limit) {
            $statement .= ' LIMIT ' . $this->limit;
        }
        return $statement;
    }

    public function prepareInsertUpdateStatement(array $attributes, array $old): string
    {
        if (!$this->model->exists) {
            return $this->prepareInsert($attributes);
        }
        return $this->prepareUpdate($attributes, $old);
    }

    public function prepareInsert(array $values): string
    {
        $table = $this->model->getTable();
        $columns = $this->columnize($values);
        $binding = implode(', ', array_map(fn() => " ? ", $values));
        return "INSERT INTO $table($columns) value ($binding)";
    }

    public function columnize(array $values, array $forUpdate = []): string
    {
        if (!empty($forUpdate)) {
            $arrayDiff = array_diff_assoc($values, $forUpdate);
            return implode(', ', array_map(fn($value) => "`$value` = ?", array_keys($arrayDiff)));
        }
        return implode(', ', array_map(fn($value) => "`$value`", array_keys($values)));
    }


    //@todo create array trite

    public function prepareUpdate(array $attributes, array $old): string
    {
        $table = $this->model->getTable();
        $columns = $this->columnize($attributes, $old);
        $primaryKey = $this->model->primaryKey;
        $id = $this->model->{$primaryKey};
        return "UPDATE $table SET $columns WHERE $table.$primaryKey=$id";
    }
}
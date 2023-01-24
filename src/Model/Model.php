<?php

namespace Apex\src\Model;


use Apex\src\App;
use Apex\src\Database\Processor;
use Apex\src\Database\Query\Builder;
use PDO;
use PDOException;
use ReflectionClass;

abstract class Model
{
    public ErrorBag $errorMessage;
    public string $primaryKey = 'id';
    protected string $table = '';
    protected array $attributes = [];
    public bool $exists = false;
    protected array $fillable = [];
    protected array $guarded = [];
    protected Builder $_builder;
    protected PDO $connection;
    protected array $original = [];
    private readonly Processor $processor;

    public function __construct()
    {
        $this->boot();
        $this->processor = new Processor($this->connection);
    }

    private function boot(): void
    {
        $this->getTable();
        $this->setBuilder(new Builder($this));
        $this->errorMessage = new ErrorBag();
    }

    static public function create(array $attributes = []): static
    {
        $model = new static;
        $model->fill($attributes);
        return $model;
    }

    public function fill(array $attributes, $original = false): void
    {
        $fillable = !$original ? $this->fillableFromArray($attributes) : $attributes;
        foreach ($fillable as $key => $value) {
            if ($this->isFillable($key) || $original) {
                $this->setAttribute($key, $value, $original);
            }
        }
    }

    private function fillableFromArray(array $attributes): array
    {
        if (!empty($this->getFillable())) {
            return array_intersect_key($attributes, array_fill_keys($this->getFillable(), 1));
        }
        return $attributes;
    }

    public function getFillable(): array
    {
        return $this->fillable;
    }

    public function isFillable($key): bool
    {
        if (in_array($key, $this->getFillable())) {
            return true;
        }

        if ($this->isGuarded($key)) {
            return false;
        }
        return empty($this->getFillable());
    }

    /**
     * Determine if the given key is guarded.
     *
     * @param string $key
     * @return bool
     */
    public function isGuarded(string $key): bool
    {

        if (empty($this->getGuarded())) {
            return false;
        }
        return $this->getGuarded() == ['*'] ||
            !empty(preg_grep('/^' . preg_quote($key, '/') . '$/i', $this->getGuarded()));
    }

    private function getGuarded(): array
    {
        return $this->guarded;
    }

    /**
     * @param string $key
     * @param string $value
     * @param bool $original
     */
    public function setAttribute(string $key, mixed $value, bool $original = false): void
    {
        if ($original) {
            $this->original[$key] = $value;
        }
        $this->attributes[$key] = $value;
    }

    /**
     * Get all the models from the database.
     *
     * @param array $columns
     * @return array|string
     */
    public static function all(array $columns = ['*']): array|string
    {
        return static::select(is_array($columns) ? $columns : func_get_args())->get();
    }

    /**
     * @return array<static>
     */
    public function get($howMany = INF, $skip = 0): array
    {
        // TODO Refactor this into the processor
        $sql = $this->getBuilder()->prepareGetStatement();
        $statement = $this->getConnection()->prepare($sql);
        $bindings = $this->getBuilder()->bindings['where'];
        foreach ($bindings as $i => $binding) {
            $statement->bindValue($i + 1, $binding);
            $sql = substr_replace($sql, $binding, strpos($sql, '?'));
        }
        $statement->execute();
        $models = [];
        try {
            foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $modelValues) {
                $model = static::prepareModel($modelValues);
                $models[] = $model;
            }
            return $models;
        } catch (PDOException $e) {
            dd($e, $sql);
        }
    }

    static private function prepareModel(array $modelValues): static
    {
        $model = new static();
        $model->exists = true;
        $model->fill($modelValues, true);
        return $model;
    }

    public function query(): Builder
    {
        return new Builder($this);
    }

    static function select(string|array $columns = ['*']): static
    {
        $model = new static();
        $model->exists = true;
        $model->getBuilder()->prepareSelect($columns);
        return $model;
    }


    /**
     * {string: condition, string: property, string: value}
     * @param array|string|callable $column
     * @param string|null $conditions
     * @param mixed $value
     * @param string $boolean
     * @return Model
     */
    public function where(array|string|callable $column, string|null $conditions = null, mixed $value = null, string $boolean = 'and'): static
    {
        $this->getBuilder()->prepareWhere($column, $conditions, $value, $boolean);
        return $this;
    }

    public function save(): bool
    {
        try {
            $insertStatement = $this->connection->prepare($this->getBuilder()->prepareInsertUpdateStatement($this->attributes, $this->original));
            $bindings = $this->prepareInsertBindings();
            foreach ($bindings as $index => $value) {
                $insertStatement->bindValue($index + 1, "$value");
            }
            $res = $insertStatement->execute();
            if (!$this->exists) {
                $id = $this->connection->lastInsertId();
                $this->setAttribute($this->primaryKey, $id);
            }
            return $res;
        } catch (PDOException $exception) {
            $this->errorMessage->addError('*', $exception->getMessage());
            return false;
        }
    }
    /**
     * @return Builder
     */
    protected function getBuilder(): Builder
    {
        return $this->_builder;
    }

    /**
     * @param Builder $builder
     */
    protected function setBuilder(Builder $builder): void
    {
        $this->_builder = $builder;
    }

    private function prepareInsertBindings(): array
    {
        return array_values(array_diff_assoc($this->attributes, $this->original));
    }

    public function find(): void
    {
        NOT_IMPLEMENTED();
    }

    public function __get(string $name)
    {
        if (!empty($this->attributes[$name])) {
            return $this->attributes[$name];
        }
        return null;
    }

    public function __set(string $name, $value): void
    {
        if ($this->isFillable($name)) {
            $this->setAttribute($name, $value);
        }
    }

    /**
     * @return PDO
     */
    public function getConnection(): PDO
    {
        return App::getInstance()->db->pdo;
    }

    public function setConnection(PDO $pdo): void
    {
        $this->connection = $pdo;
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        if (empty($this->table)) {
            $reflect = new ReflectionClass(static::class);
            $this->table = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $reflect->getShortName()));
        }
        return $this->table;
    }

    public function orWhere(string $column, string $condition, mixed $value): static
    {
        $this->where($column, $condition, $value, 'or');
        return $this;
    }

    public function orWhereIn(string $column, mixed $value): static
    {
        $this->where($column, 'IN', $value, 'or');
        return $this;
    }

    public function firstWhere(string $column, string $condition, mixed $value): static|null
    {
        $this->where(...func_get_args());
        if (empty($this->get())) {
            return null;
        }
        return $this->get()[0];
    }
}
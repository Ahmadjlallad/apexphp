<?php

namespace Apex\src\Model;


use Apex\src\Database\Processor;
use Apex\src\Database\Query\Builder;
use PDO;
use PDOException;
use ReflectionClass;

abstract class Model
{
    public ErrorBag $errorBag;
    public ?string $primaryKey = null;
    public bool $exists = false;
    protected string $table = '';
    protected array $attributes = [];
    protected array $fillable = [];
    protected array $guarded = [];
    protected Builder $_builder;
    protected PDO $connection;
    protected array $original = [];
    protected array $hidden = [];
    private readonly Processor $processor;

    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
        $this->boot();
        $this->processor = new Processor($this->connection);
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

    private function boot(): void
    {
        $this->getTable();
        $this->setBuilder(new Builder($this));
        if (empty($this->primaryKey)) {
            $this->primaryKey = $this->getPrimaryId();
        }
        $this->errorBag = new ErrorBag();
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

    public function getPrimaryId()
    {
        return $this->getBuilder()->getTablePrimaryKey($this->getTable());
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

    static public function create(array $attributes = []): static
    {
        return new static($attributes);
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

    /**
     * @return PDO
     */
    public function getConnection(): PDO
    {
        return app()->db->pdo;
    }

    public function setConnection(PDO $pdo): void
    {
        $this->connection = $pdo;
    }

    private function prepareModel(array $modelValues): static
    {
        $model = new static();
        $model->fill($modelValues, true);
        $this->prepareExistingModels();
        return $model;
    }

    public function prepareExistingModels(): void
    {
        $this->exists = true;
        $this->hideAttributes($this->hidden);
    }

    private function hideAttributes(array $modifiedHidden = null): void
    {
        foreach ($modifiedHidden ?? $this->hidden as $hidden) {
            unset($this->attributes[$hidden]);
        }
        $this->syncOriginal();
    }

    public function syncOriginal(): static
    {
        $this->original = $this->getAttributes();
        return $this;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    static function select(string|array $columns = ['*']): static
    {
        $model = new static();
        $model->exists = true;
        $model->getBuilder()->prepareSelect($columns);
        return $model;
    }

    public static function one(): ?static
    {
        if (!empty($model = static::select()->limit(1)->get(1))) {
            return $model[0];
        }
        return null;
    }

    public function limit(int $limit): static
    {
        $this->getBuilder()->limit = $limit;
        return $this;
    }

    public static function getTableName(): string
    {
        return (new static())->getTable();
    }

    public function query(): Builder
    {
        return new Builder($this);
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
            $this->errorBag->addError('*', $exception->getMessage());
            return false;
        }
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
        if (method_exists($this, $name)) {
            return $this->$name()->get();
        }
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

    public function orWhere(string $column, string $condition, mixed $value): static
    {
        $this->where($column, $condition, $value, 'or');
        return $this;
    }

    /**
     * {string: condition, string: property, string: value}
     * @param array|string|callable $column
     * @param string|null $conditions
     * @param mixed $value
     * @param string $boolean
     * @return Model
     */
    public function where(array|string|callable $column, ?string $conditions = null, mixed $value = null, string $boolean = 'and'): static
    {
        $this->getBuilder()->prepareWhere($column, $conditions, $value, $boolean);
        return $this;
    }

    public function orWhereIn(string $column, mixed $value): static
    {
        $this->where($column, 'IN', $value, 'or');
        return $this;
    }

    public function firstWhere(array|string $column, string $condition = '=', mixed $value = null): static|null
    {
        $this->where(...func_get_args());
        if (empty($this->get())) {
            return null;
        }
        return $this->get()[0];
    }

    public function makeVisible(array $becomeVisible): static
    {
        $this->hidden = array_diff($this->hidden, $becomeVisible);
        return $this;
    }

    public function makeHidden(array $array): static
    {
        $this->hidden = array_merge($this->hidden, $array);
        return $this;
    }

    public function belongsToMany(string $model, string $throwModel, array $throwRelation, array $targetTable)
    {
        return $this->getBuilder()->belongsToMany($this, $model, $throwModel, $throwRelation, $targetTable);
    }

    public function hasMany(string $model, string $foreignKey, string $localKey)
    {
        return $this->getBuilder()->hasMay($this, $model, $foreignKey, $localKey);
    }
}
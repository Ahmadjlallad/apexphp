<?php

namespace Apex\src\Model;


use Apex\src\App;
use Apex\src\Database\Query\Builder;
use PDO;
use PDOException;
use ReflectionClass;

abstract class Model
{
    public ErrorBag $errorMessage;
    protected $primaryKey = 'id';
    protected string $table = '';
    protected array $attributes = [];
    protected bool $exists = false;
    protected array $fillable = [];
    protected array $guarded = [];
    protected Builder $_builder;
    protected PDO $connection;

    public function __construct()
    {
        $this->boot();
    }

    private function boot(): void
    {
        $this->setBuilder(new Builder($this));
        $this->errorMessage = new ErrorBag();
    }

    static public function create(array $attributes = []): static
    {
        $model = new static;
        $model->fill($attributes);
        return $model;
    }

    public function fill(array $attributes): void
    {
        $fillable = $this->fillableFromArray($attributes);
        foreach ($fillable as $key => $value) {
            if ($this->isFillable($key)) {
                $this->setAttribute($key, $value);
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
     */
    public function setAttribute(string $key, string $value): void
    {
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
        return static::query()->get(
            is_array($columns) ? $columns : func_get_args()
        );
    }

    public function query(): Builder
    {
        return new Builder($this);
    }

    static function select()
    {
        NOT_IMPLEMENTED();
    }

    public function where()
    {
        NOT_IMPLEMENTED();
    }

    public function save(): bool
    {
        try {
            if ($this->exists) {
                $this->update();
            }
            $sql = $this->getBuilder()->prepareSqlInsert($this->attributes);
            $insertStatement = $this->connection->prepare($sql);
            $bindings = $this->prepareInsertBindings();
            foreach ($bindings as $index => $value) {
                $insertStatement->bindValue($index + 1, "$value");
            }
            return $insertStatement->execute();
        } catch (PDOException $exception) {
            $this->errorMessage->addError('*', $exception->getMessage());
            return false;
        }
    }

    public function update()
    {









        $this->getBuilder()->prepareSqlInsert($this->attributes);
        NOT_IMPLEMENTED();
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
        return array_values($this->attributes);
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
        $this->attributes[$name] = $value;
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
}
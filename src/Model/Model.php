<?php

namespace Apex\src\Model;


use Apex\src\App;
use Apex\src\Database\Query\Builder;
use Apex\src\Database\Query\ColumnBlueprint;
use PDO;
use PDOException;
use ReflectionClass;

abstract class Model
{
    public $errorMessage = [];
    protected $primaryKey = 'id';
    protected string $table = '';
    protected array $attributes = [];
    protected bool $exists = false;
    private Builder $_builder;
    private PDO $connection;
    protected array $fillable = [];
    protected array $guarded = ['name'];

    public function __construct()
    {
        $this->boot();
    }

    private function boot(): void
    {
        $this->setBuilder(new Builder($this));
//        $this->getColumns();

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
            if (!$this->isFillable($key)) {
                $this->attributes[$key] = $value;
            }
        }
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

    public function where()
    {
        NOT_IMPLEMENTED();
    }

    public function save(): void
    {
        try {
            if ($this->exists) {
                $this->update();
            }
            $sql = $this->getBuilder()->prepareSqlInsert($this->attributes);
            $insartStatement = $this->connection->prepare($sql);
            $bindings = $this->prepareInsartBindings();
            foreach ($bindings as $index => $value) {
                $insartStatement->bindValue($index + 1, "'$value'");
            }

            dd($insartStatement->execute());
        } catch (PDOException $exception) {
            dd($exception);
        }
    }

    public function find()
    {

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
        $this->$name = $value;
    }

    public function setConnection(PDO $pdo): void
    {
        $this->connection = $pdo;
    }

    private function getColumns(): void
    {
        try {
            $columnsStatement = $this->getConnection()->query('DESCRIBE ' . $this->getTable());
            $columnsStatement->execute();
            /** @var ColumnBlueprint[] $columns */
            $columns = $columnsStatement->fetchAll(PDO::FETCH_CLASS);
            foreach ($columns as $column) {
                $this->{$column->Field} = null;
            }
        } catch (PDOException $e) {
            dd($e);
        }
    }

    /**
     * @return PDO
     */
    public function getConnection(): PDO
    {
        return App::getInstance()->db->pdo;
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

    private function isGarded(): bool
    {
        return count($this->guarded) > 0;
    }

    private function fa()
    {
    }

    public function getFillable()
    {
        return $this->fillable;
    }

    public function isFillable($key)
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

    private function fillableFromArray(array $attributes): array
    {
        if (!empty($this->getFillable())) {
            return array_intersect_key($attributes, $this->getFillable());
        }
        return $attributes;
    }

    /**
     * @param string $key
     * @param string $value
     */
    public function setAttribute(string $key, string $value): void
    {
        $this->attributes[$key] = $value;
    }

    public function update()
    {
        NOT_IMPLEMENTED();
    }

    private function prepareInsartBindings(): array
    {
        return array_values($this->attributes);
    }

}
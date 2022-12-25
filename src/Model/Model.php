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

    public function __construct()
    {
        $this->boot();
    }

    static public function create(array $attributes = []): static
    {
        $model = new static;
        $model->fill($attributes);
        return $model;
    }

    public function fill(array $attributes): void
    {
        foreach ($attributes as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public function where()
    {
        NOT_IMPLEMENTED();
    }

    public function save()
    {
        NOT_IMPLEMENTED();
    }

    private PDO $connection;

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

    public function find()
    {

    }

    public static function query(): Builder
    {
        return new Builder();
    }

    /**
     * @return PDO
     */
    public function getConnection(): PDO
    {
        return App::getInstance()->db->pdo;
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

    private function boot(): void
    {
        $this->getColumns();
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        if (empty($this->table)) {
            $reflect = new ReflectionClass(static::class);
            return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $reflect->getShortName()));
        }
        return $this->table;
    }

    public function __set(string $name, $value): void
    {
        $this->$name = $value;
    }

    public function __get(string $name)
    {
        return $this->$name;
    }
}
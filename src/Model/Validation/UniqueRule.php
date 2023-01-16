<?php

namespace Apex\src\Model\Validation;

use Apex\src\Model\Model;
use PDO;
use Rakit\Validation\MissingRequiredParameterException;
use Rakit\Validation\Rule;

class UniqueRule extends Rule
{
    protected $message = ":attribute :value has been used";
    protected PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function table(string $table): static
    {
        $this->params['table'] = $table;
        return $this;
    }

    public function column(string $column): static
    {
        $this->params['column'] = $column;
        return $this;
    }

    public function model(string $model): static
    {
        $this->params['model'] = new $model;
        return $this;
    }

    /**
     * @throws MissingRequiredParameterException
     */
    public function check($value): bool
    {
        $model = $this->parameter('model');
        $table = $this->parameter('table');
        $column = $this->parameter('column');
        $this->requireParameters(['column']);
        if (empty($model) && empty($table)) {
            throw new MissingRequiredParameterException("Missing required parameter 'table' or 'model' on rule 'Unique'");
        }
        // getting parameters
        /** @var $class Model */

        if (empty($model)) {
            $stmt = $this->pdo->prepare("select count(*) as count from `{$table}` where `$column` = :value");
            $stmt->bindParam(':value', $value);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            // true for valid, false for invalid
            return intval($data['count']) === 0;
        }
        /** @var Model $model */
        return empty($model->firstWhere($column, '=', $value));
    }
}
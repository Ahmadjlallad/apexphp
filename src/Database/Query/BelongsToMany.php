<?php

namespace Apex\src\Database\Query;

use Apex\src\App;
use Apex\src\Database\Processor;
use Apex\src\Model\Model;

class BelongsToMany implements Relation
{
    private Processor $processor;

    /**
     * @param Model $parent
     * @param Model $model
     * @param Model $throwModel
     */
    public function __construct(private readonly Model  $parent,
                                private readonly string $model,
                                private readonly string $throwModel,
                                private readonly array  $throwRelation,
                                private readonly array  $targetTable)
    {
        $this->processor = App::getInstance()->container->resolve(Processor::class);
    }

    /**
     * @return array|Model[]
     */
    function get(): array
    {
        if (!$sql = $this->build()) {
            return [];
        }
        $query = $this->processor->query($sql);
        $query->execute();
        $models = $query->fetchAll(\PDO::FETCH_CLASS, $this->model);
        /**
         * @var $model Model
         */
        foreach ($models as $model) {
            $model->prepareExistingModels();
        }
        return $models;
    }

    /**
     * @return string
     */
    function build(): ?string
    {
        $parentId = $this->parent->getPrimaryId();
        $parentIdValue = $this->parent->$parentId;
        if (!$parentIdValue) {
            return null;
        }
        return "SELECT trTbl.* FROM {$this->parent->getTable()} pTbl
                    JOIN {$this->throwModel::getTableName()} 
                        thTbl on thTbl.{$this->throwRelation[1]} = pTbl.{$this->throwRelation[0]}
                    JOIN {$this->model::getTableName()} 
                        trTbl on trTbl.{$this->targetTable[1]} = thTbl.{$this->targetTable[0]} 
                    where pTbl.$parentId = {$this->parent->$parentId}";
    }
}
<?php

namespace Apex\src\Database\Query;

use Apex\src\App;
use Apex\src\Database\Processor;
use Apex\src\Model\Model;
use Exception;
use PDO;

class HasMany implements Relation
{
    private Processor $processor;

    /**
     * @param Model $parent
     * @param Model $model
     * @param string $forgingId
     * @param string $localId
     */
    public function __construct(public Model $parent, public string $model, public string $forgingId, public string $localId)
    {
        $this->processor = App::getInstance()->container->resolve(Processor::class);
    }

    /**
     * @return Model[]
     * @throws Exception
     */
    public function get(): ?array
    {
        if (!$sql = $this->build()) {
            return [];
        }
        $query = $this->processor->query($sql);
        $query->execute();
        $models = $query->fetchAll(PDO::FETCH_CLASS, $this->model);
        foreach ($models as $model) {
            $model->prepareExistingModels();
        }
        return $models;
    }

    public function build(): ?string
    {
        $targetTable = $this->model::getTableName();
        $parentTable = $this->parent->getTable();
        $parentTableId = $this->parent->primaryKey;
        $parentId = $this->parent->{$parentTableId};
        if (!$parentId) {
            return null;
        }
        return "SELECT tr.* FROM $parentTable pt JOIN $targetTable tr ON tr.$this->forgingId = pt.$this->localId WHERE pt.$parentTableId = $parentId";
    }
}
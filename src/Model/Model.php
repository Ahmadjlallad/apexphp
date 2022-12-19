<?php

namespace Apex\src\Model;


abstract class Model
{
    public $errorMessage = [];
    protected $primaryKey = 'id';
    protected $table;

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
}
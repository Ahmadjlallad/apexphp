<?php

namespace Apex\src\Database\Query;

use Apex\src\Model\Model;

interface Relation
{
    /**
     * @return Model[]|null
     */
    function get(): ?array;

    function build(): ?string;
}
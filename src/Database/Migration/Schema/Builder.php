<?php

namespace Apex\src\Database\Migration\Schema;

interface Builder
{
    public function create(string $table, array $columns, bool $checkIfNotExist = false): string;

    public function addForeignKey(array $config): string;
}

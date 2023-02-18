<?php

namespace Apex\migrations;

use Apex\src\Database\Migration\Migration;
use Apex\src\Database\Migration\Schema\Definition\Column;
use Apex\src\Database\Migration\Schema\Definition\ColumnDefinition;
use Apex\src\Database\Migration\Schema\Definition\DefaultType;

class m0001_initial extends Migration
{
    public function up(): void
    {
        $this->createTable('user', [
            'id' => Column::add()->primaryKey(),
            'name' => Column::add()->string(),
            'email' => Column::add()->string()->unique()->notNull(),
            'birth_date' => Column::add()->date(),
            'password' => Column::add()->string()->notNull(),
            'created_at' => Column::add()->timestamps()->default(ColumnDefinition::CURRENT_TIMESTAMP, DefaultType::BUILTIN),
            'updated_at' => Column::add()->timestamps()
                ->default(ColumnDefinition::CURRENT_TIMESTAMP, DefaultType::BUILTIN)
                ->onUpdate(ColumnDefinition::CURRENT_TIMESTAMP)
        ]);
    }

    public function down(): void
    {

    }
}
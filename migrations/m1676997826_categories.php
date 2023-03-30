<?php

namespace Apex\migrations;
use Apex\src\Database\Migration\Migration;
use Apex\src\Database\Migration\Schema\Definition\Column;

class m1676997826_categories extends Migration
{
    public function up(): void
    {
        $this->createTable('categories', [
            'category_id' => Column::add()->primaryKey(),
            'name' => Column::add()->string(),
            'option_id' => Column::add()->bigint(),
            'used_from'  => Column::add()->datetime(),
            'created_at' => Column::add()->timestamps(),
            'updated_at' => Column::add()->timestamps(),
        ]);
        $this->createForeignKey(['table' => 'posts', 'pointsOn' => 'category_id', 'fTable' => 'categories', 'fColumn' => 'category_id']);
    }

    public function down(): void
    {
        //
    }
}
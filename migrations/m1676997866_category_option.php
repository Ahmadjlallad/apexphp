<?php

namespace Apex\migrations;
use Apex\src\Database\Migration\Migration;
use Apex\src\Database\Migration\Schema\Definition\Column;

class m1676997866_category_option extends Migration
{
    public function up(): void
    {
        $this->createTable('category_option', [
            'category_option_id' => Column::add()->primaryKey(),
            'option_id' => Column::add()->bigint(),
            'category_id' => Column::add()->bigint(),
        ]);
        $this->createForeignKey(['table' => 'category_option', 'pointsOn' => 'category_id', 'fTable' => 'categories', 'fColumn' => 'category_id']);
        $this->createForeignKey(['table' => 'category_option', 'pointsOn' => 'option_id', 'fTable' => 'options', 'fColumn' => 'option_id']);
    }

    public function down(): void
    {
        //posts_categories_options_relations
    }
}
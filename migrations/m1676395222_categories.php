<?php

namespace Apex\migrations;
use Apex\src\Database\Migration\Migration;
use Apex\src\Database\Migration\Schema\Definition\Column;

class m1676395222_categories extends Migration
{
    public function up(): void
    {
        $this->createTable('categories', ['id' => Column::add()->primaryKey(), 'name' => Column::add()->text(), 'sub_categories_id' => Column::add()->bigint()]);
    }
// create_relation_ads_categories
    public function down(): void
    {
        //
    }
}
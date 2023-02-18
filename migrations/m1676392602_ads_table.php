<?php

namespace Apex\migrations;

use Apex\src\Database\Migration\Migration;
use Apex\src\Database\Migration\Schema\Definition\Column;

class m1676392602_ads_table extends Migration
{
    public function up(): void
    {
        $this->createTable('ads', ['id' => Column::add()->primaryKey(), 'user_id' => Column::add()->bigint(), 'category_id' => Column::add()->bigint()]);
        $this->createForeignKey(['table' => 'ads', 'fTable' => 'user', 'pointsOn' => 'user_id', 'fColumn' => 'id']);
    }

    public function down(): void
    {
        $this->dropTable('ads');
    }
}
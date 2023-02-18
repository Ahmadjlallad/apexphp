<?php

namespace Apex\migrations;
use Apex\src\Database\Migration\Migration;

class m1676395399_create_relation_ads_categories extends Migration
{
    public function up(): void
    {
        $this->createForeignKey(['table' => 'ads', 'pointsOn' => 'category_id', 'fTable' => 'categories', 'fColumn' => 'id']);
    }

    public function down(): void
    {
        //
    }
}
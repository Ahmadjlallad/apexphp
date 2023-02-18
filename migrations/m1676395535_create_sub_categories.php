<?php

namespace Apex\migrations;
use Apex\src\Database\Migration\Migration;
use Apex\src\Database\Migration\Schema\Definition\Column;

class m1676395535_create_sub_categories extends Migration
{
    public function up(): void
    {
        $this->createTable('sub_categories', ['id' => Column::add()->primaryKey()]);
    }

    public function down(): void
    {
        //
    }
}
<?php

namespace Apex\migrations;

use Apex\src\Database\Migration\Migration;
use Apex\src\Database\Migration\Schema\Definition\Column;

class m1676997835_options extends Migration
{
    public function up(): void
    {
        $this->createTable('options', [
            'option_id' => Column::add()->primaryKey(),
            'name' => Column::add()->string(),
            'value' => Column::add()->string(),
            'created_at' => Column::add()->timestamps(),
            'updated_at' => Column::add()->timestamps()
        ]);
    }

    public function down(): void
    {
        //
    }
}
<?php

namespace Apex\migrations;
use Apex\src\Database\Migration\Migration;
use Apex\src\Database\Migration\Schema\Definition\Column;

class m1679145667_post_options extends Migration
{
    public function up(): void
    {
        $this->createTable('post_options', [
            'post_option_id' => Column::add()->primaryKey(),
            'option_id' => Column::add()->bigint(),
            'post_id'=> Column::add()->bigint()
        ]);
        $this->createForeignKey(['table' => 'post_options', 'pointsOn' => 'option_id', 'fTable' => 'options', 'fColumn' => 'option_id']);
        $this->createForeignKey(['table' => 'post_options', 'pointsOn' => 'post_id', 'fTable' => 'posts', 'fColumn' => 'post_id']);
    }

    public function down(): void
    {
        //
    }
}
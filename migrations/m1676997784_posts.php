<?php

namespace Apex\migrations;

use Apex\src\Database\Migration\Migration;
use Apex\src\Database\Migration\Schema\Definition\Column;

class m1676997784_posts extends Migration
{
    public function up(): void
    {
        $this->createTable('posts', [
            'post_id' => Column::add()->primaryKey(),
            'user_id' => Column::add()->bigint(),
            'category_id' => Column::add()->bigint()->unique(),
            'price' => Column::add()->decimal(6, 4),
            'created_at' => Column::add()->timestamps(),
            'updated_at' => Column::add()->timestamps()
        ]);
        $this->createForeignKey(['table' => 'posts', 'pointsOn' => 'user_id', 'fTable' => 'users', 'fColumn' => 'user_id']);
    }

    public function down(): void
    {
        $this->dropTable('posts');
    }
}
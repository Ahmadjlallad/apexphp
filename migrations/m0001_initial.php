<?php

namespace Apex\migrations;

use Apex\src\Database\Migration\Migration;
use Apex\src\Database\Migration\Schema\Schema;

class m0001_initial extends Migration
{
    public function up(): void
    {
        $s = new Schema();
        echo $this->create('test', [
            'a' => $s->string()->notNull()->default()->after('t'),
            'b' => (new Schema())->text()->notNull(),
        ]);
    }

    public function down(): void
    {

    }
}
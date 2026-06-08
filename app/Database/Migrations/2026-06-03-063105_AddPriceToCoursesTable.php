<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPriceToCoursesTable extends Migration
{
    public function up()
    {
        $fields = [

            'price' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
                'after'      => 'description'
            ]

        ];

        $this->forge->addColumn('courses', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('courses', 'price');
    }
}
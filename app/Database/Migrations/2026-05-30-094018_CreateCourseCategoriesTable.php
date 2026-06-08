<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCourseCategoriesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'category_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],

            'category_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],

            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],

            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('category_id', true);

        $this->forge->createTable('course_categories');
    }

    public function down()
    {
        $this->forge->dropTable('course_categories');
    }
}
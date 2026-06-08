<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTopicsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'topic_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],

            'subject_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],

            'topic_name' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
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

        $this->forge->addKey('topic_id', true);
        $this->forge->addKey('subject_id');

        $this->forge->addForeignKey(
            'subject_id',
            'subjects',
            'subject_id',
            'CASCADE',
            'CASCADE'
        );

        $this->forge->createTable('topics');
    }

    public function down()
    {
        $this->forge->dropTable('topics');
    }
}
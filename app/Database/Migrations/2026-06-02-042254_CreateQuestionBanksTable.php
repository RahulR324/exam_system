<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateQuestionBanksTable extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'questionbank_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'parent_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],

            'questionbank_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
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

        $this->forge->addKey('questionbank_id', true);
        $this->forge->addKey('parent_id');

        $this->forge->createTable('question_banks');
    }

    public function down()
    {
        $this->forge->dropTable('question_banks');
    }
}
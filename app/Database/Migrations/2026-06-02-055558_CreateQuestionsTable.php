<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateQuestionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'question_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'questionbank_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],

            'question' => [
                'type' => 'TEXT',
            ],

            'option_a' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],

            'option_b' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],

            'option_c' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],

            'option_d' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],

            'correct_answer' => [
                'type'       => 'VARCHAR',
                'constraint' => 1,
                'comment'    => 'A,B,C,D'
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

        $this->forge->addKey('question_id', true);
        $this->forge->addKey('questionbank_id');

        $this->forge->createTable('questions');
    }

    public function down()
    {
        $this->forge->dropTable('questions');
    }
}
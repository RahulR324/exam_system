<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateQuestionsTableForQuestionModel extends Migration
{
    public function up()
    {
        // Rename question -> question_text
        $this->forge->modifyColumn('questions', [
            'question' => [
                'name'       => 'question_text',
                'type'       => 'LONGTEXT',
                'null'       => false,
            ],
        ]);

        // Add explanation column
        $this->forge->addColumn('questions', [
            'explanation' => [
                'type' => 'LONGTEXT',
                'null' => true,
                'after' => 'correct_answer',
            ],
        ]);
    }

    public function down()
    {
        // Remove explanation
        $this->forge->dropColumn('questions', 'explanation');

        // Rename question_text back to question
        $this->forge->modifyColumn('questions', [
            'question_text' => [
                'name'       => 'question',
                'type'       => 'TEXT',
                'null'       => false,
            ],
        ]);
    }
}
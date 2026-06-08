<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateExamsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([

            // Primary Key
            'exam_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],

            // Foreign Key → courses
            'course_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],

            // Exam details
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],

            'date' => [
                'type' => 'DATE',
                'null' => true,
            ],

            'start_time' => [
                'type' => 'TIME',
                'null' => true,
            ],

            'end_time' => [
                'type' => 'TIME',
                'null' => true,
            ],

            'duration' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'comment' => 'Duration in minutes',
            ],

            // timestamps
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],

            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        // Primary key
        $this->forge->addKey('exam_id', true);

        // FK → courses
        $this->forge->addForeignKey(
            'course_id',
            'courses',
            'course_id',
            'CASCADE',
            'CASCADE'
        );

        $this->forge->createTable('exams');
    }

    public function down()
    {
        $this->forge->dropTable('exams');
    }
}
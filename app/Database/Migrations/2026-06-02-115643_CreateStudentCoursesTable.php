<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStudentCoursesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'student_course_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'student_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],

            'course_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],

            'assigned_date' => [
                'type' => 'DATETIME',
                'null' => false,
            ],

            'completion_date' => [
                'type' => 'DATETIME',
                'null' => true,
            ],

            'progress' => [
                'type'       => 'INT',
                'constraint' => 3,
                'default'    => 0,
            ],

            'completed_status' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'comment'    => '0=Not Completed, 1=Completed'
            ],

        ]);

        $this->forge->addKey('student_course_id', true);

        $this->forge->addForeignKey(
            'student_id',
            'students',
            'student_id',
            'CASCADE',
            'CASCADE'
        );

        $this->forge->addForeignKey(
            'course_id',
            'courses',
            'course_id',
            'CASCADE',
            'CASCADE'
        );

        $this->forge->createTable('student_courses');
    }

    public function down()
    {
        $this->forge->dropTable('student_courses');
    }
}
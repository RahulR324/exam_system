<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTopicMaterialsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'material_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'topic_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],

            'material_title' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],

            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            'material_type' => [
                'type'       => 'ENUM',
                'constraint' => ['pdf', 'youtube', 'video'],
            ],

            'file_path' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],

            'youtube_url' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
                'null'       => true,
            ],

            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],

            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ]
        ]);

        $this->forge->addKey('material_id', true);
        $this->forge->addKey('topic_id');

        $this->forge->createTable('topic_materials');
    }

    public function down()
    {
        $this->forge->dropTable('topic_materials');
    }
}
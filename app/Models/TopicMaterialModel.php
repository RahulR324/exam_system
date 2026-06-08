<?php

namespace App\Models;

use CodeIgniter\Model;

class TopicMaterialModel extends Model
{
    protected $table = 'topic_materials';

    protected $primaryKey = 'material_id';

    protected $returnType = 'array';

    protected $useTimestamps = true;

    protected $allowedFields = [
        'topic_id',
        'material_title',
        'description',
        'material_type',
        'file_path',
        'youtube_url'
    ];
}
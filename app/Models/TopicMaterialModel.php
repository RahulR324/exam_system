<?php

namespace App\Models;

class TopicMaterialModel extends Base_model
{
    protected $table = 'topic_materials';

    protected $primaryKey = 'material_id';

    protected $allowedFields = [
        'topic_id',
        'material_title',
        'description',
        'material_type',
        'file_path',
        'youtube_url'
    ];


    protected $useSoftDeletes = false;

    protected $softDelete = false;
}
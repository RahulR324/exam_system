<?php

namespace App\Models;

class TopicModel extends Base_model
{
    protected $table = 'topics';

    protected $primaryKey = 'topic_id';

    protected $allowedFields = [
        'subject_id',
        'topic_name',
        'description'
    ];

    protected $useTimestamps = true;

    protected $useSoftDeletes = false;

    protected $softDelete = false;
}
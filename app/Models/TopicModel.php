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



    protected $useSoftDeletes = false;

    protected $softDelete = false;
}
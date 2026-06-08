<?php

namespace App\Models;

use CodeIgniter\Model;

class TopicModel extends Model
{
    protected $table = 'topics';

    protected $primaryKey = 'topic_id';

    protected $allowedFields = [
        'subject_id',
        'topic_name',
        'description'
    ];

    protected $returnType = 'array';
}
<?php

namespace App\Models;

use CodeIgniter\Model;

class SubjectModel extends Model
{
    protected $table = 'subjects';

    protected $primaryKey = 'subject_id';

    protected $allowedFields = [
        'course_id',
        'subject_name',
        'description'
    ];

    protected $returnType = 'array';
}
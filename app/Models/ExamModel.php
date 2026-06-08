<?php

namespace App\Models;

use CodeIgniter\Model;

class ExamModel extends Model
{
    protected $table = 'exams';
    protected $primaryKey = 'exam_id';

    protected $allowedFields = [
        'course_id',
        'title',
        'date',
        'start_time',
        'end_time',
        'duration'
    ];

    protected $returnType = 'array';
    protected $useTimestamps = false;
}
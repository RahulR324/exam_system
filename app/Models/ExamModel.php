<?php

namespace App\Models;

class ExamModel extends Base_model
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

    protected $useTimestamps = true;
    protected $useSoftDeletes = false;

    protected $softDelete = false;
}
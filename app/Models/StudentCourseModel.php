<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentCourseModel extends Model
{
    protected $table = 'student_courses';

    protected $primaryKey = 'student_course_id';

    protected $returnType = 'array';

    protected $allowedFields = [

        'student_id',
        'course_id',
        'assigned_date',
        'completion_date',
        'progress',
        'completed_status'

    ];

    protected $useTimestamps = false;
}
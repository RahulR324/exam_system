<?php

namespace App\Models;

class StudentCourseModel extends Base_model
{
    protected $table = 'student_courses';

    protected $primaryKey = 'student_course_id';

    protected $allowedFields = [
        'student_id',
        'course_id',
        'assigned_date',
        'completion_date',
        'progress',
        'completed_status'
    ];

    protected $useTimestamps = false;

    protected $useSoftDeletes = false;

    protected $softDelete = false;
}
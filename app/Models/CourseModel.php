<?php

namespace App\Models;

class CourseModel extends Base_model
{
    protected $table = 'courses';

    protected $primaryKey = 'course_id';

    protected $allowedFields = [
        'category_id',
        'course_name',
        'description',
        'price',
        'thumbnail'
    ];


    protected $useTimestamps = true;
    protected $useSoftDeletes = false;

    protected $softDelete = false;
}
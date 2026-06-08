<?php

namespace App\Models;

use CodeIgniter\Model;

class CourseModel extends Model
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

    protected $returnType = 'array';
}
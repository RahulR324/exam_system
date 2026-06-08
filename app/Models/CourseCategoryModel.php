<?php

namespace App\Models;

use CodeIgniter\Model;

class CourseCategoryModel extends Model
{
    protected $table = 'course_categories';

    protected $primaryKey = 'category_id';

    protected $allowedFields = [
        'category_name',
        'description'
    ];

    protected $returnType = 'array';
}
<?php

namespace App\Models;

class CourseCategoryModel extends Base_model
{
    protected $table = 'course_categories';

    protected $primaryKey = 'category_id';

    protected $allowedFields = [
        'category_name',
        'description'
    ];



    protected $useSoftDeletes = false;

    protected $softDelete = false;
}
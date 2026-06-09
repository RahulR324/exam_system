<?php

namespace App\Models;

class SubjectModel extends Base_model
{
    protected $table = 'subjects';

    protected $primaryKey = 'subject_id';

    protected $allowedFields = [
        'course_id',
        'subject_name',
        'description'
    ];



    protected $useSoftDeletes = false;

    protected $softDelete = false;
}
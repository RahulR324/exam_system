<?php

namespace App\Models;

class StudentModel extends Base_model
{
    protected $table = 'students';

    protected $primaryKey = 'student_id';

    protected $allowedFields = [
        'name',
        'email',
        'phone',
        'password',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $useSoftDeletes = false;

    protected $softDelete = false;
}
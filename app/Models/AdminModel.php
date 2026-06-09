<?php

namespace App\Models;

class AdminModel extends Base_model
{
    protected $table = 'admins';

    protected $primaryKey = 'id';

    protected $allowedFields = [
        'username',
        'password'
    ];

    protected $useTimestamps = false;

    protected $useSoftDeletes = false;

    protected $softDelete = false;
}
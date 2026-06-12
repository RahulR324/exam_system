<?php

namespace App\Models;

class QuestionBankModel extends Base_model
{
    protected $table = 'question_banks';

    protected $primaryKey = 'questionbank_id';

    protected $allowedFields = [
        'questionbank_name',
        'parent_id',
        'description'
    ];

    protected $useTimestamps = true;

    protected $useSoftDeletes = false;

    protected $softDelete = false;
}
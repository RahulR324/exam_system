<?php

namespace App\Models;

use CodeIgniter\Model;

class QuestionBankModel extends Model
{
    protected $table = 'question_banks';

    protected $primaryKey = 'questionbank_id';

    protected $returnType = 'array';

    protected $allowedFields = [
        'questionbank_name',
        'parent_id',
        'description'
    ];

    protected $useTimestamps = false;
}
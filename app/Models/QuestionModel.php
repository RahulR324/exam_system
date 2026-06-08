<?php

namespace App\Models;

use CodeIgniter\Model;

class QuestionModel extends Model
{
    protected $table = 'questions';

    protected $primaryKey = 'question_id';

    protected $returnType = 'array';

    protected $allowedFields = [
        'questionbank_id',
        'question_text',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'correct_answer',
        'explanation'
    ];

    protected $useTimestamps = true;
}
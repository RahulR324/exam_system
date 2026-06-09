<?php

namespace App\Models;

class QuestionModel extends Base_model
{
    protected $table = 'questions';

    protected $primaryKey = 'question_id';

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


    protected $useSoftDeletes = false;

    protected $softDelete = false;
}
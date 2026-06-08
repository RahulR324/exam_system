<?php

namespace App\Controllers;
use App\Models\ExamModel;
class ExamController extends BaseController
{
    public function index()
    {
        $examModel = new ExamModel();
        $data['exams'] = $examModel->findAll();
        echo "<pre>";
        print_r($data['exams']);
    }
}
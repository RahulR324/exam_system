<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StudentModel;
use App\Models\ExamModel;
use App\Models\QuestionModel;
use App\Models\AnswerModel;
use CodeIgniter\API\ResponseTrait;
use Firebase\JWT\JWT;

class AuthController extends BaseController
{
    use ResponseTrait;

    public function index()
    {
        return view('login');
    }

    /**
     * LOGIN - Validates student credentials and generates JWT token
     * Also sets session variables for exam session management
     */
    public function login()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        if (!$username || !$password) {
            return $this->respond([
                'status'  => false,
                'message' => 'Username and password are required'
            ], 400);
        }

        $studentModel = new StudentModel();
        $examModel    = new ExamModel();
        $answerModel  = new AnswerModel();

        date_default_timezone_set('Asia/Kolkata');

        // Get today's date
        $today    = date('Y-m-d');
        $allExams = $examModel
                        ->where('date', $today)
                        ->findAll();

        $exam = null;
        $currentTimestamp = time();

        // Find active exam based on current time
        foreach ($allExams as $ex) {
            $start = strtotime($ex['date'] . ' ' . $ex['start_time']);
            $end   = strtotime($ex['date'] . ' ' . $ex['end_time']);

            if ($currentTimestamp >= $start && $currentTimestamp <= $end) {
                $exam = $ex;
                break;
            }
        }

        // No active exam found
        if (!$exam) {
            return $this->respond([
                'status'  => false,
                'message' => 'No Active Exam Available'
            ], 400);
        }

        // Check student credentials (Excel-imported data)
        $student = $studentModel
                        ->where('username', $username)
                        ->where('password', $password)
                        ->where('exam_id', $exam['id'])
                        ->first();

        if (!$student) {
            return $this->respond([
                'status'  => false,
                'message' => 'Invalid Username or Password'
            ], 401);
        }

        // Check if student already attended THIS exam
        $alreadyAttended = $answerModel
            ->join('questions', 'questions.id = answers.question_id')
            ->where('answers.student_id', $student['id'])
            ->where('questions.exam_id', $exam['id'])
            ->countAllResults();

        if ($alreadyAttended > 0) {
            return $this->respond([
                'status'    => false,
                'message'   => 'You have already attended this exam',
                'submitted' => true
            ], 403);
        }

        // ✅ SET SESSION VARIABLES (CRITICAL!)
        session()->set('student_id', $student['id']);
        session()->set('exam_id', $exam['id']);

        log_message('info', "Student {$student['id']} logged in for exam {$exam['id']}");

        // JWT GENERATION
        $key = getenv('JWT_SECRET_KEY'); 
        $iat = time(); 
        $exp = $iat + 7200; // 2 hour expiry
        
        $payload = [
            'iss'        => 'ExamPortalBackend',
            'aud'        => 'ExamPortalFrontend', 
            'iat'        => $iat,
            'exp'        => $exp,
            'student_id' => $student['id'],      
            'exam_id'    => $exam['id']  
        ];

        $token = JWT::encode($payload, $key, 'HS256');

        return $this->respond([
            'status'     => true,
            'message'    => 'Login successful',
            'token'      => $token,
            'student_id' => $student['id'],
            'exam_id'    => $exam['id']
        ], 200);
    }

    public function instructions()
    {
        $studentId = session()->get('student_id');
        $examId    = session()->get('exam_id');

        if (!$studentId || !$examId) {
            return redirect()->to('/');
        }

        $studentModel = new StudentModel();
        $examModel    = new ExamModel();

        $student = $studentModel->find($studentId);
        $exam    = $examModel->find($examId);

        if (!$student || !$exam) {
            return redirect()->to('/');
        }

        date_default_timezone_set('Asia/Kolkata');

        $now          = date('Y-m-d H:i:s');
        $examStartStr = $exam['date'] . ' ' . $exam['start_time'];
        $examEndStr   = $exam['date'] . ' ' . $exam['end_time'];

        $currentTime = strtotime($now);
        $examStart   = strtotime($examStartStr);
        $examEnd     = strtotime($examEndStr);

        if ($examEnd <= $examStart) {
            $examEnd += 86400;
        }

        if ($currentTime < $examStart) {
            return view('result', [
                'message' => 'Exam Not Started Yet'
            ]);
        }

        if ($currentTime >= $examEnd) {
            return view('result', [
                'message' => 'Exam Time Over'
            ]);
        }

        return view('instructions', [
            'student' => $student,
            'exam'    => $exam
        ]);
    }

    /**
     * EXAM PAGE - Main exam interface
     * Handles timer, question display, and answer submission
     */
    public function exam()
    {
        helper('jwt');

        // Detect React API request
        $authHeader = $this->request->getServer('HTTP_AUTHORIZATION');
        $isReactRequest = false;

        if ($authHeader) {
            $isReactRequest = true;
        }

        // ================================
        // REACT AUTH USING JWT
        // ================================
        if ($isReactRequest) {
            try {
                preg_match('/Bearer\s(\S+)/', $authHeader, $matches);
                $token = $matches[1];
                $key = getenv('JWT_SECRET_KEY');

                $decoded = \Firebase\JWT\JWT::decode(
                    $token,
                    new \Firebase\JWT\Key($key, 'HS256')
                );

                $studentId = $decoded->student_id;
                $examId    = $decoded->exam_id;
            } catch (\Exception $e) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Invalid Token'
                ])->setStatusCode(401);
            }
        } else {
            // ================================
            // HTML SESSION AUTH
            // ================================
            $studentId = session()->get('student_id');
            $examId    = session()->get('exam_id');

            if (!$studentId || !$examId) {
                return redirect()->to('/');
            }
        }

        $studentModel  = new StudentModel();
        $examModel     = new ExamModel();
        $questionModel = new QuestionModel();
        $answerModel   = new AnswerModel();

        $student = $studentModel->find($studentId);
        $exam    = $examModel->find($examId);

        if (!$student || !$exam) {
            if ($isReactRequest) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Student or Exam not found'
                ])->setStatusCode(404);
            }
            return redirect()->to('/');
        }

        // ================================
        // CHECK ALREADY SUBMITTED
        // ================================
        $alreadySubmitted = $answerModel
            ->join('questions', 'questions.id = answers.question_id')
            ->where('answers.student_id', $studentId)
            ->where('questions.exam_id', $examId)
            ->countAllResults();

        if (
            $alreadySubmitted > 0 &&
            $this->request->getPost('submit_exam') !== '1'
        ) {
            if ($isReactRequest) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Exam already submitted',
                    'submitted' => true
                ])->setStatusCode(403);
            }
            return redirect()->to('/result');
        }

        // ================================
        // TIMER LOGIC
        // ================================
        date_default_timezone_set('Asia/Kolkata');
        $now = date('Y-m-d H:i:s');
        $examStartStr = $exam['date'] . ' ' . $exam['start_time'];
        $examEndStr   = $exam['date'] . ' ' . $exam['end_time'];

        $currentTime = strtotime($now);
        $examStart = strtotime($examStartStr);
        $examEnd = strtotime($examEndStr);

        if ($examEnd <= $examStart) {
            $examEnd += 86400;
        }

        if ($currentTime < $examStart) {
            if ($isReactRequest) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Exam Not Started Yet'
                ])->setStatusCode(403);
            }
            return view('result', [
                'message' => 'Exam Not Started Yet'
            ]);
        }

        if ($currentTime >= $examEnd) {
            if ($isReactRequest) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Exam Time Over'
                ])->setStatusCode(403);
            }
            return view('result', [
                'message' => 'Exam Time Over'
            ]);
        }

        // ================================
        // SESSION TIMER
        // ================================
        $sessionKey = 'exam_start_' . $studentId . '_' . $examId;

        if (!session()->has($sessionKey)) {
            session()->set($sessionKey, $currentTime);
        }

        $studentStart = session()->get($sessionKey);
        $studentExamEnd = $studentStart + ($exam['duration'] * 60);
        $actualEndTime = min($studentExamEnd, $examEnd);
        $remainingSeconds = $actualEndTime - $currentTime;

        if ($remainingSeconds <= 0) {
            if ($isReactRequest) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Exam Time Over'
                ])->setStatusCode(403);
            }
            return view('result', [
                'message' => 'Exam Time Over'
            ]);
        }

        // ================================
        // FETCH QUESTIONS
        // ================================
        $questions = $questionModel
            ->where('exam_id', $examId)
            ->findAll();

        if (empty($questions)) {
            if ($isReactRequest) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'No questions found for this exam'
                ])->setStatusCode(404);
            }
            return view('result', [
                'message' => 'No questions found for this exam'
            ]);
        }
        // ================================
        // SUBMIT EXAM
        // ================================
        if ($this->request->getPost('submit_exam') === '1') {
            foreach ($questions as $q) {
                $selected = $this->request->getPost($q['id']);
                $answerModel->insert([
                    'student_id'      => $studentId,
                    'question_id'     => $q['id'],
                    'selected_answer' => $selected ?? ''
                ]);
            }

            // ================================
            // CALCULATE SCORE
            // ================================
            $score = 0;
            foreach ($questions as $q) {
                $selected = $this->request->getPost($q['id']);
                if ($selected == $q['correct_answer']) {
                    $score++;
                }
            }

            $totalQuestions = count($questions);
            session()->remove($sessionKey);

            log_message('info', "Exam submitted - Student: $studentId, Exam: $examId");

            // ================================
            // REACT RESPONSE
            // ================================
            if ($isReactRequest) {
                return $this->response->setJSON([
                    'status' => true,
                    'message' => 'Exam submitted successfully',
                    'score' => $score,
                    'total' => $totalQuestions
                ])->setStatusCode(200);
            }

            // ================================
            // HTML RESPONSE
            // ================================
            return redirect()->to('/result');
        }

        // ================================
        // HTML VIEW ONLY
        // ================================
        return view('exam', [
            'student'   => $student,
            'exam'      => $exam,
            'questions' => $questions,
            'duration'  => $remainingSeconds
        ]);
    }

    /**
     * RESULT PAGE - Shows exam score
     */
    public function result()
    {
        $studentId = session()->get('student_id');
        $examId    = session()->get('exam_id');

        if (!$studentId || !$examId) {
            return redirect()->to('/');
        }

        $studentModel  = new StudentModel();
        $examModel     = new ExamModel();
        $answerModel   = new AnswerModel();
        $questionModel = new QuestionModel();

        $student = $studentModel->find($studentId);
        $exam    = $examModel->find($examId);

        if (!$student || !$exam) {
            return redirect()->to('/');
        }

        $totalQuestions = $questionModel
                            ->where('exam_id', $examId)
                            ->countAllResults();

        $studentAnswers = $answerModel
            ->join('questions', 'questions.id = answers.question_id')
            ->where('answers.student_id', $studentId)
            ->where('questions.exam_id', $examId)
            ->findAll();

        if (count($studentAnswers) === 0) {
            return view('result', [
                'message' => 'Exam Submitted Successfully',
                'score'   => 0,
                'total'   => $totalQuestions,
                'student' => $student,
                'exam'    => $exam
            ]);
        }

        $score = 0;
        foreach ($studentAnswers as $ans) {
            $question = $questionModel->find($ans['question_id']);
            if (
                $question &&
                $ans['selected_answer'] == $question['correct_answer']
            ) {
                $score++;
            }
        }

        log_message('info', "Result calculated - Student: $studentId, Score: $score/$totalQuestions");

        return view('result', [
            'message' => 'Exam Submitted Successfully',
            'score'   => $score,
            'total'   => $totalQuestions,
            'student' => $student,
            'exam'    => $exam
        ]);
    }
        
    // FOR REACT APP
    public function getExamData()
    {
        helper('jwt');

        $authHeader = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$authHeader) {
            $authHeader = $this->request->getHeaderLine('Authorization');
        }

        if (
            !$authHeader ||
            !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)
        ) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Token missing'
            ])->setStatusCode(401);
        }

        try {
            $token = $matches[1];
            $key = getenv('JWT_SECRET_KEY');

            $decoded = JWT::decode(
                $token,
                new \Firebase\JWT\Key($key, 'HS256')
            );

            $studentId = $decoded->student_id;
            $examId    = $decoded->exam_id;
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Invalid token'
            ])->setStatusCode(401);
        }

        $studentModel  = new StudentModel();
        $examModel     = new ExamModel();
        $questionModel = new QuestionModel();
        $answerModel   = new AnswerModel();
        
        $student = $studentModel->find($studentId);
        $exam = $examModel->find($examId);

        if (!$student || !$exam) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Student or Exam not found'
            ])->setStatusCode(404);
        }

        // =============================================================
        // ✨ OPTIMIZED ALREADY SUBMITTED CHECK (CHANGED FROM 403 TO 200)
        // =============================================================
        $alreadySubmitted = $answerModel
            ->join('questions', 'questions.id = answers.question_id')
            ->where('answers.student_id', $studentId)
            ->where('questions.exam_id', $examId)
            ->countAllResults();

        if ($alreadySubmitted > 0) {
            return $this->response->setJSON([
                'status'    => true, // Return true so react response mapping doesn't break
                'message'   => 'Exam already submitted',
                'submitted' => true,
                'questions' => [], // Empty payload to block view reuse
                'exam_meta' => [
                    'title' => $exam['title']
                ]
            ])->setStatusCode(200); 
        }

        date_default_timezone_set('Asia/Kolkata');
        $now = date('Y-m-d H:i:s');

        $examStartStr = $exam['date'] . ' ' . $exam['start_time'];
        $examEndStr   = $exam['date'] . ' ' . $exam['end_time'];

        $currentTime = strtotime($now);
        $examStart = strtotime($examStartStr);
        $examEnd = strtotime($examEndStr);

        if ($examEnd <= $examStart) {
            $examEnd += 86400;
        }

        if ($currentTime < $examStart) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Exam Not Started Yet'
            ])->setStatusCode(403);
        }

        if ($currentTime >= $examEnd) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Exam Time Over',
                'debug' => [
            'now' => $now,
            'examStart' => $examStartStr,
            'examEnd' => $examEndStr,
            'currentTime' => $currentTime,
            'examEndTS' => $examEnd
        ]
            ])->setStatusCode(403);
        }

        $sessionKey = 'exam_start_' . $studentId . '_' . $examId;

        if (!session()->has($sessionKey)) {
            session()->set($sessionKey, $currentTime);
        }

        $studentStart = session()->get($sessionKey);
        $studentExamEnd = $studentStart + ($exam['duration'] * 60);
        $actualEndTime = min($studentExamEnd, $examEnd);
        $remainingSeconds = $actualEndTime - $currentTime;

        if ($remainingSeconds <= 0) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Exam Time Over'
            ])->setStatusCode(403);
        }

        $questions = $questionModel
            ->where('exam_id', $examId)
            ->findAll();

        if (!$questions) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'No questions found'
            ])->setStatusCode(404);
        }

        $formattedQuestions = [];
        foreach ($questions as $q) {
            $formattedQuestions[] = [
                'id'      => $q['id'],
                'text'    => $q['question'],
                'options' => [
                    $q['option1'],
                    $q['option2'],
                    $q['option3'],
                    $q['option4']
                ]
            ];
        }

        return $this->response->setJSON([
            'status' => true,
            'student' => [
                'id'   => $student['id'],
                'name' => $student['name'],
                'register_number' => $student['register_number']
            ],
            'exam_meta' => [
                'id'                => $exam['id'],
                'title'             => $exam['title'],
                'duration_seconds'  => $remainingSeconds,
                'total_questions'   => count($formattedQuestions),
                'date'              => $exam['date'],
                'start_time'        => $exam['start_time'],
                'end_time'          => $exam['end_time']
            ],
            'questions' => $formattedQuestions
        ])->setStatusCode(200);
    }

    // REACT RESULT API
    public function getResultData()
    {
        $authHeader = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$authHeader) {
            $authHeader = $this->request->getHeaderLine('Authorization');
        }

        if (
            !$authHeader ||
            !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)
        ) {
            return $this->respond([
                'status' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        try {
            $token = $matches[1];
            $key = getenv('JWT_SECRET_KEY');

            $decoded = \Firebase\JWT\JWT::decode(
                $token,
                new \Firebase\JWT\Key($key, 'HS256')
            );

            $studentId = $decoded->student_id;
            $examId    = $decoded->exam_id;
        } catch (\Exception $e) {
            return $this->respond([
                'status' => false,
                'message' => 'Invalid Token'
            ], 401);
        }

        $studentModel  = new StudentModel();
        $examModel     = new ExamModel();
        $questionModel = new QuestionModel();
        $answerModel   = new AnswerModel();

        $student = $studentModel->find($studentId);
        $exam = $examModel->find($examId);

        if (!$student || !$exam) {
            return $this->respond([
                'status' => false,
                'message' => 'Student or Exam not found'
            ], 404);
        }

        $questions = $questionModel
                        ->where('exam_id', $examId)
                        ->findAll();

        $totalQuestions = count($questions);

        $studentAnswers = $answerModel
            ->join('questions', 'questions.id = answers.question_id')
            ->where('answers.student_id', $studentId)
            ->where('questions.exam_id', $examId)
            ->findAll();

        $score = 0;
        foreach ($studentAnswers as $ans) {
            $question = $questionModel->find($ans['question_id']);
            if (
                $question &&
                $ans['selected_answer'] == $question['correct_answer']
            ) {
                $score++;
            }
        }

        return $this->respond([
            'status' => true,
            'student' => [
                'name' => $student['name']
            ],
            'exam' => [
                'title' => $exam['title']
            ],
            'score' => $score,
            'total' => $totalQuestions,
            'percentage' => $totalQuestions > 0 ? round(($score / $totalQuestions) * 100) : 0
        ]);
    }
}
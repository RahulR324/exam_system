<?php

namespace App\Controllers;
use App\Models\Base_model;
use App\Models\ExamModel;
use App\Models\QuestionModel;
use App\Models\StudentModel;
use App\Models\AdminModel;
use App\Models\CourseCategoryModel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class AdminController extends BaseController
{
    public function loginPage()
    {
        return view('admin/login');
    }

    //admin login processing
    public function login()
{
    $username = $this->request->getPost('username');
    $password = $this->request->getPost('password');

    $adminModel = new AdminModel();

    $admin = $adminModel
        ->where('username', $username)
        ->first();

    if (!$admin || !password_verify($password, $admin['password'])) {

        return redirect()
            ->to('/admin')
            ->with('error', 'Invalid Credentials');
    }

    session()->set([
        'admin_id' => $admin['id'],
        'admin_logged_in' => true
    ]);

    return redirect()->to('/admin/dashboard');
}

//admin logout
    public function logout()
    {
        session()->destroy();

        return redirect()->to('/admin');
    }

    // Admin Dashboard Page
public function dashboard()
{
    if (!session()->get('admin_logged_in')) {
        return redirect()->to('/admin');
    }

    $examModel            = new \App\Models\ExamModel();
    $studentModel         = new \App\Models\StudentModel();
    $courseModel          = new \App\Models\CourseModel();
    $questionModel        = new \App\Models\QuestionModel();
    $courseCategoryModel  = new \App\Models\CourseCategoryModel();

    $data = [

        'examCount'           => $examModel->getNumRows(),

        'studentCount'        => $studentModel->getNumRows(),

        'courseCount'         => $courseModel->getNumRows(),

        'questionCount'       => $questionModel->getNumRows(),

        'coursecategorycount' => $courseCategoryModel->getNumRows(),

        'recentStudents'      => $studentModel
                                    ->orderBy('student_id', 'DESC')
                                    ->findAll(5)

    ];

    return view('admin/dashboard', $data);
}

//course categories management
public function courseCategories()
{
    if (!session()->get('admin_logged_in')) {
        return redirect()->to('/admin');
    }

    $courseCategoryModel = new \App\Models\CourseCategoryModel();

    $search = $this->request->getGet('search');

    if (!empty($search)) {
        $courseCategoryModel
            ->groupStart()
            ->like('category_name', $search)
            ->orLike('description', $search)
            ->groupEnd();
    }

    $data = [
        'categories' => $courseCategoryModel
                            ->orderBy('category_id', 'ASC')
                            ->findAll(),
        'search'     => $search
    ];

    return view('admin/course_categories', $data);
}

// manage course category (both add and edit)
public function manageCourseCategory()
{
    // Authentication Check
    if (!session()->get('admin_logged_in')) {
        return redirect()->to('/admin');
    }

    // Form Submit
    if ($this->request->getMethod() === 'POST') {

        $categoryModel = new \App\Models\CourseCategoryModel();

        $data = [
            'category_name' => trim($this->request->getPost('category_name')),
            'description'   => trim($this->request->getPost('description'))
        ];

        $categoryModel->add($data);

        return redirect()
            ->to('/admin/course_categories')
            ->with('success', 'Course category created successfully');
    }

    return view('admin/add_course_category');
}

// Edit course category
public function editCourseCategory($id)
{
    if (!session()->get('admin_logged_in')) {
        return redirect()->to('/admin');
    }

    $categoryModel = new \App\Models\CourseCategoryModel();

    // Handle Update
    if ($this->request->getMethod() === 'POST') {

        $data = [
            'category_name' => $this->request->getPost('category_name'),
            'description'   => $this->request->getPost('description')
        ];

        $categoryModel->edit($data, [
            'category_id' => $id
        ]);

        return redirect()
            ->to('/admin/course_categories')
            ->with('success', 'Course category updated successfully');
    }

    // Fetch Category
    $data['category'] = $categoryModel->find($id);

    if (!$data['category']) {
        return redirect()
            ->to('/admin/course_categories')
            ->with('error', 'Category not found');
    }

    return view('admin/edit_course_category', $data);
}

// Delete course category
public function deleteCourseCategory($id)
{
    if (!session()->get('admin_logged_in')) {
        return redirect()->to('/admin');
    }

    $categoryModel = new \App\Models\CourseCategoryModel();

    $category = $categoryModel->find($id);

    if (!$category) {
        return redirect()
            ->to('/admin/course_categories')
            ->with('error', 'Category not found');
    }

    $categoryModel->delete($id);

    return redirect()
        ->to('/admin/course_categories')
        ->with('success', 'Course category deleted successfully');
}

//courses
public function courses()
{
    if (!session()->get('admin_logged_in')) {
        return redirect()->to('/admin');
    }

    $categoryModel = new \App\Models\CourseCategoryModel();
    $courseModel   = new \App\Models\CourseModel();

    // Get all categories
    $categories = $categoryModel->get(
        null,
        '*',
        ['category_name' => 'ASC']
    )->getResultArray();

    // Get courses under each category
    foreach ($categories as &$category) {

        $category['courses'] = $courseModel->get(
            ['category_id' => $category['category_id']]
        )->getResultArray();
    }

    $data['categories'] = $categories;

    return view('admin/courses', $data);
}

//add course
public function manageCourse()
{
    if (!session()->get('admin_logged_in')) {
        return redirect()->to('/admin');
    }

    $courseModel = new \App\Models\CourseModel();
    $categoryModel = new \App\Models\CourseCategoryModel();

    // POST Request
    if ($this->request->getMethod() === 'POST') {

        $thumbnail = $this->request->getFile('thumbnail');

        $thumbnailName = null;

        if ($thumbnail && $thumbnail->isValid() && !$thumbnail->hasMoved()) {

            $thumbnailName = $thumbnail->getRandomName();

            $thumbnail->move(
                FCPATH . 'uploads/course_thumbnails',
                $thumbnailName
            );
        }

        $data = [
            'category_id' => $this->request->getPost('category_id'),
            'course_name' => $this->request->getPost('course_name'),
            'description' => $this->request->getPost('description'),
            'price'       => $this->request->getPost('price'),
            'thumbnail'   => $thumbnailName
        ];

        $courseModel->add($data);

        return redirect()
            ->to('/admin/courses')
            ->with('success', 'Course added successfully');
    }

    // GET Request
    $data['categories'] = $categoryModel
        ->get(
            null,
            '*',
            ['category_name' => 'ASC']
        )
        ->getResultArray();

    return view('admin/add_course', $data);
}

//edit course
public function editCourse($id)
{
    if (!session()->get('admin_logged_in')) {
        return redirect()->to('/admin');
    }

    $courseModel = new \App\Models\CourseModel();
    $categoryModel = new \App\Models\CourseCategoryModel();

    // POST REQUEST
    if ($this->request->getMethod() === 'POST') {

        $course = $courseModel->find($id);

        if (!$course) {
            return redirect()
                ->to('/admin/courses')
                ->with('error', 'Course not found');
        }

        $thumbnailName = $course['thumbnail'];

        $thumbnail = $this->request->getFile('thumbnail');

        if ($thumbnail && $thumbnail->isValid() && !$thumbnail->hasMoved()) {

            $thumbnailName = $thumbnail->getRandomName();

            $thumbnail->move(
                FCPATH . 'uploads/course_thumbnails',
                $thumbnailName
            );
        }

        $data = [
            'category_id' => $this->request->getPost('category_id'),
            'course_name' => $this->request->getPost('course_name'),
            'description' => $this->request->getPost('description'),
            'price'       => $this->request->getPost('price'),
            'thumbnail'   => $thumbnailName
        ];

        $courseModel->edit($data, [
            'course_id' => $id
        ]);

        return redirect()
            ->to('/admin/courses')
            ->with('success', 'Course updated successfully');
    }

    // GET REQUEST

    $data['course'] = $courseModel->find($id);

    if (!$data['course']) {
        return redirect()
            ->to('/admin/courses')
            ->with('error', 'Course not found');
    }

    $data['categories'] = $categoryModel->get(
        null,
        '*',
        ['category_name' => 'ASC']
    )->getResultArray();

    return view('admin/edit_course', $data);
}

// delete course
public function deleteCourse($id)
{
    if (!session()->get('admin_logged_in')) {
        return redirect()->to('/admin');
    }

    $courseModel = new \App\Models\CourseModel();

    $course = $courseModel->find($id);

    if (!$course) {
        return redirect()
            ->to('/admin/courses')
            ->with('error', 'Course not found');
    }

    // Delete thumbnail file if exists
    if (!empty($course['thumbnail'])) {

        $filePath = FCPATH . 'uploads/course_thumbnails/' . $course['thumbnail'];

        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    $courseModel->delete($id);

    return redirect()
        ->to('/admin/courses')
        ->with('success', 'Course deleted successfully');
}

//Exam page
public function exams()
{
    if (!session()->get('admin_logged_in')) {
        return redirect()->to('/admin');
    }

    $categoryModel = new \App\Models\CourseCategoryModel();
    $courseModel   = new \App\Models\CourseModel();
    $examModel     = new \App\Models\ExamModel();

    $selectedCategory = $this->request->getGet('category_id');
    $selectedCourse   = $this->request->getGet('course_id');

    $data['selectedCategory'] = $selectedCategory;
    $data['selectedCourse']   = $selectedCourse;

    // Categories Filter
    $data['categories_filter'] = $categoryModel
        ->get(
            null,
            '*',
            ['category_name' => 'ASC']
        )
        ->getResultArray();

    // Courses Filter
    $courseWhere = [];

    if (!empty($selectedCategory)) {
        $courseWhere['category_id'] = $selectedCategory;
    }

    $data['courses_filter'] = $courseModel
        ->get(
            $courseWhere,
            '*',
            ['course_name' => 'ASC']
        )
        ->getResultArray();

    // Display Courses
    $displayCourseWhere = [];

    if (!empty($selectedCategory)) {
        $displayCourseWhere['category_id'] = $selectedCategory;
    }

    if (!empty($selectedCourse)) {
        $displayCourseWhere['course_id'] = $selectedCourse;
    }

    $data['courses'] = $courseModel
        ->get(
            $displayCourseWhere,
            '*',
            ['course_name' => 'ASC']
        )
        ->getResultArray();

    // Exams under each course
    foreach ($data['courses'] as &$course) {

        $course['exams'] = $examModel
            ->get(
                ['course_id' => $course['course_id']],
                '*',
                ['exam_id' => 'DESC']
            )
            ->getResultArray();
    }

    return view('admin/exams', $data);
}

//delete exam
public function deleteExam($id)
{
    if (!session()->get('admin_logged_in')) {
        return redirect()->to('/admin');
    }

    $examModel = new \App\Models\ExamModel();

    $examModel->delete($id);

    return redirect()
        ->to('/admin/exams')
        ->with('success', 'Exam deleted successfully');
}


//add exam
public function manageExam()
{
    if (!session()->get('admin_logged_in')) {
        return redirect()->to('/admin');
    }

    $courseModel   = new \App\Models\CourseModel();
    $categoryModel = new \App\Models\CourseCategoryModel();

    // GET Request
    if ($this->request->getMethod() === 'GET') {

        $data['categories'] = $categoryModel->get(
            null,
            '*',
            ['category_name' => 'ASC']
        )->getResultArray();

        $data['courses'] = $courseModel->get(
            null,
            '*',
            ['course_name' => 'ASC']
        )->getResultArray();

        return view('admin/add_exam', $data);
    }

    // POST Request
    $examModel = new \App\Models\ExamModel();

    $data = [
        'course_id'  => $this->request->getPost('course_id'),
        'title'      => $this->request->getPost('title'),
        'date'       => $this->request->getPost('date'),
        'start_time' => $this->request->getPost('start_time'),
        'end_time'   => $this->request->getPost('end_time'),
        'duration'   => $this->request->getPost('duration')
    ];

    $examModel->add($data);

    return redirect()
        ->to('/admin/exams')
        ->with('success', 'Exam created successfully');
}

//edit exam
public function manageEditExam($id)
{
    if (!session()->get('admin_logged_in')) {
        return redirect()->to('/admin');
    }

    $examModel = new \App\Models\ExamModel();
    $courseModel = new \App\Models\CourseModel();

    // GET → Load Edit Form
    if ($this->request->getMethod() === 'GET') {

        $data['exam'] = $examModel->find($id);

        if (!$data['exam']) {
            return redirect()
                ->to('/admin/exams')
                ->with('error', 'Exam not found');
        }

        $data['courses'] = $courseModel->get(
            null,
            '*',
            ['course_name' => 'ASC']
        )->getResultArray();

        return view('admin/edit_exam', $data);
    }

    // POST → Update Exam
    $updateData = [
        'course_id'  => $this->request->getPost('course_id'),
        'title'      => $this->request->getPost('title'),
        'date'       => $this->request->getPost('date'),
        'start_time' => $this->request->getPost('start_time'),
        'end_time'   => $this->request->getPost('end_time'),
        'duration'   => $this->request->getPost('duration'),
    ];

    $examModel->edit($updateData, [
        'exam_id' => $id
    ]);

    return redirect()
        ->to('/admin/exams')
        ->with('success', 'Exam updated successfully');
}

//subjects page
public function subjects($course_id)
{
    if (!session()->get('admin_logged_in')) {
        return redirect()->to('/admin');
    }

    $courseModel = new \App\Models\CourseModel();
    $subjectModel = new \App\Models\SubjectModel();

    // Get Course Details
    $data['course'] = $courseModel->find($course_id);

    if (!$data['course']) {
        return redirect()
            ->to('/admin/courses')
            ->with('error', 'Course not found');
    }

    // Get Subjects
    $data['subjects'] = $subjectModel->get(
        ['course_id' => $course_id],
        '*',
        ['subject_name' => 'ASC']
    )->getResultArray();

    return view('admin/subjects', $data);
}

//add subject
public function addSubject($course_id)
{
    if (!session()->get('admin_logged_in')) {
        return redirect()->to('/admin');
    }

    $courseModel = new \App\Models\CourseModel();
    $subjectModel = new \App\Models\SubjectModel();

    // Get Course Info
    $data['course'] = $courseModel->find($course_id);

    if (!$data['course']) {
        return redirect()
            ->to('/admin/courses')
            ->with('error', 'Course not found');
    }

    // HANDLE POST
    if ($this->request->getMethod() === 'POST') {

        $dataInsert = [
            'course_id'    => $course_id,
            'subject_name' => $this->request->getPost('subject_name'),
            'description'  => $this->request->getPost('description')
        ];

        $subjectModel->add($dataInsert);

        return redirect()
            ->to('/admin/subjects/' . $course_id)
            ->with('success', 'Subject added successfully');
    }

    return view('admin/add_subject', $data);
}

//delete subject
public function deleteSubject($subject_id)
{
    if (!session()->get('admin_logged_in')) {
        return redirect()->to('/admin');
    }

    $db = \Config\Database::connect();

    // Get subject first (to know course_id for redirect)
    $subject = $db->table('subjects')
        ->where('subject_id', $subject_id)
        ->get()
        ->getRowArray();

    if (!$subject) {
        return redirect()->to('/admin/courses')
            ->with('error', 'Subject not found');
    }

    $db->table('subjects')
        ->where('subject_id', $subject_id)
        ->delete();

    return redirect()->to('admin/subjects/' . $subject['course_id'])
        ->with('success', 'Subject deleted successfully');
}

//edit subject
public function editSubject($subject_id)
{
    if (!session()->get('admin_logged_in')) {
        return redirect()->to('/admin');
    }

    $subjectModel = new \App\Models\SubjectModel();
    $courseModel  = new \App\Models\CourseModel();

    // Get Subject
    $data['subject'] = $subjectModel->get(
        ['subject_id' => $subject_id]
    )->getRowArray();

    if (!$data['subject']) {
        return redirect()
            ->to('/admin/courses')
            ->with('error', 'Subject not found');
    }

    // Get Course
    $data['course'] = $courseModel->get(
        ['course_id' => $data['subject']['course_id']]
    )->getRowArray();

    // Update Subject
    if ($this->request->getMethod() === 'POST') {

        $updateData = [
            'subject_name' => $this->request->getPost('subject_name'),
            'description'  => $this->request->getPost('description'),
        ];

        $subjectModel->edit(
            $updateData,
            ['subject_id' => $subject_id]
        );

        return redirect()
            ->to('/admin/subjects/' . $data['subject']['course_id'])
            ->with('success', 'Subject updated successfully');
    }

    return view('admin/edit_subject', $data);
}

//topics page
public function topics($subject_id)
{
    if (!session()->get('admin_logged_in')) {
        return redirect()->to('/admin');
    }

    $subjectModel = new \App\Models\SubjectModel();
    $courseModel  = new \App\Models\CourseModel();
    $topicModel   = new \App\Models\TopicModel();

    // Subject Details
    $subject = $subjectModel->get(
        ['subject_id' => $subject_id]
    )->getRowArray();

    if (!$subject) {
        return redirect()
            ->to('/admin/courses')
            ->with('error', 'Subject not found');
    }

    $data['subject'] = $subject;

    // Course Details
    $data['course'] = $courseModel->get(
        ['course_id' => $subject['course_id']]
    )->getRowArray();

    // Topics + Material Count
    $data['topics'] = $topicModel->get_join(
        [
            [
                'topic_materials',
                'topic_materials.topic_id = topics.topic_id',
                'left'
            ]
        ],
        [
            'topics.subject_id' => $subject_id
        ],
        'topics.*, COUNT(topic_materials.material_id) as material_count',
        ['topics.topic_id' => 'ASC'],
        null,
        'topics.topic_id'
    )->getResultArray();

    return view('admin/topics', $data);
}

//add topic
public function addTopic($subjectId)
{
    if (!session()->get('admin_logged_in')) {
        return redirect()->to('/admin');
    }

    $subjectModel = new \App\Models\SubjectModel();
    $topicModel   = new \App\Models\TopicModel();

    // Get Subject Info
    $subject = $subjectModel->get(
        ['subject_id' => $subjectId]
    )->getRowArray();

    if (!$subject) {
        return redirect()
            ->to('/admin/courses')
            ->with('error', 'Subject not found');
    }

    $data['subject'] = $subject;

    // POST Request
    if ($this->request->getMethod() === 'POST') {

        $topicData = [
            'subject_id'  => $subjectId,
            'topic_name'  => $this->request->getPost('topic_name'),
            'description' => $this->request->getPost('description'),
        ];

        $topicModel->add($topicData);

        return redirect()
            ->to('/admin/topics/' . $subjectId)
            ->with('success', 'Topic added successfully');
    }

    return view('admin/add_topic', $data);
}

//delete topic
public function deleteTopic($id)
{
    // Check admin session
    if (!session()->get('admin_logged_in')) {
        return redirect()->to('/admin');
    }

    $topicModel = new \App\Models\TopicModel();

    // Check if topic exists
    $topic = $topicModel->find($id);

    if (!$topic) {
        return redirect()->to('/admin/courses')
                         ->with('error', 'Topic not found.');
    }

    // Delete topic
    $topicModel->delete($id);

    return redirect()->to('/admin/topics/'.$topic['subject_id'])
                 ->with('success', 'Topic deleted successfully.');
}

//edit topic
public function editTopic($id)
{
    if (!session()->get('admin_logged_in')) {
        return redirect()->to('/admin');
    }

    $topicModel = new \App\Models\TopicModel();

    // Get Topic
    $topic = $topicModel->get([
        'topic_id' => $id
    ])->getRowArray();

    if (!$topic) {
        return redirect()
            ->to('/admin/courses')
            ->with('error', 'Topic not found');
    }

    // Update Topic
    if ($this->request->getMethod(true) === 'POST') {

        $data = [
            'topic_name'  => $this->request->getPost('topic_name'),
            'description' => $this->request->getPost('description'),
        ];

        $topicModel->edit($data, [
            'topic_id' => $id
        ]);

        return redirect()
            ->to('/admin/topics/' . $topic['subject_id'])
            ->with('success', 'Topic updated successfully');
    }

    return view('admin/edit_topic', [
        'topic' => $topic
    ]);
}

//question bank page
public function questionBanks($parentId = null)
{
    if (!session()->get('admin_logged_in')) {
        return redirect()->to('/admin');
    }

    $bankModel = new \App\Models\QuestionBankModel();
    $questionModel = new \App\Models\QuestionModel();

    $search = trim($this->request->getGet('search'));

    // SEARCH MODE
    if (!empty($search)) {

        $questions = $questionModel->get_join(
            [
                [
                    'question_banks',
                    'question_banks.questionbank_id = questions.questionbank_id',
                    'left'
                ]
            ],
            [],
            'questions.*, question_banks.questionbank_name'
        )->getResultArray();

        // Filter search manually
        $questions = array_filter($questions, function ($question) use ($search) {
            return stripos($question['question_text'], $search) !== false ||
                   stripos($question['option_a'], $search) !== false ||
                   stripos($question['option_b'], $search) !== false ||
                   stripos($question['option_c'], $search) !== false ||
                   stripos($question['option_d'], $search) !== false;
        });

        return view('admin/question_banks', [
            'banks'      => [],
            'questions'  => $questions,
            'parentId'   => null,
            'path'       => [],
            'search'     => $search,
            'isSearch'   => true
        ]);
    }

    // GET CHILD BANKS
    $banks = $bankModel->get(
        ['parent_id' => $parentId],
        '*',
        ['questionbank_name' => 'ASC']
    )->getResultArray();

    // GET QUESTIONS
    $questions = [];

    if ($parentId) {
        $questions = $questionModel->get(
            ['questionbank_id' => $parentId]
        )->getResultArray();
    }

    // BREADCRUMB PATH
    $path = [];
    $tempId = $parentId;

    while ($tempId) {

        $node = $bankModel->get(
            ['questionbank_id' => $tempId]
        )->getRowArray();

        if (!$node) {
            break;
        }

        array_unshift($path, $node);

        $tempId = $node['parent_id'];
    }

    return view('admin/question_banks', [
        'banks'      => $banks,
        'questions'  => $questions,
        'parentId'   => $parentId,
        'path'       => $path,
        'search'     => '',
        'isSearch'   => false
    ]);
}

//question bank path
private function getQuestionBankPath($id)
{
    $model = new \App\Models\QuestionBankModel();

    $path = [];

    while ($id) {
        $node = $model->find($id);

        if (!$node) break;

        array_unshift($path, $node);

        $id = $node['parent_id'];
    }

    return $path;
}

//question bank treebuilder
private function buildQuestionBankTree($items, $parentId = null)
{
    $branch = [];

    foreach ($items as $item) {

        if ($item['parent_id'] == $parentId) {

            $children = $this->buildQuestionBankTree(
                $items,
                $item['questionbank_id']
            );

            if ($children) {
                $item['children'] = $children;
            } else {
                $item['children'] = [];
            }

            $branch[] = $item;
        }
    }

    return $branch;
}

//add question bank
public function addQuestionBank()
{
    if (!session()->get('admin_logged_in')) {
        return redirect()->to('/admin');
    }

    $questionBankModel = new \App\Models\QuestionBankModel();

    // POST REQUEST
    if ($this->request->getMethod() === 'POST') {

        $parentId = $this->request->getPost('parent_id');

        $data = [
            'questionbank_name' => $this->request->getPost('questionbank_name'),
            'parent_id'         => !empty($parentId) ? $parentId : null,
            'description'       => $this->request->getPost('description')
        ];

        $questionBankModel->add($data);

        return redirect()
            ->to('/admin/question_banks')
            ->with('success', 'Question Bank Added Successfully');
    }

    // GET REQUEST
    $data['questionBanks'] = $questionBankModel->get(
        null,
        '*',
        ['questionbank_name' => 'ASC']
    )->getResultArray();

    return view('admin/add_question_bank', $data);
}

//edit question bank
public function editQuestionBank($id)
{
    if (!session()->get('admin_logged_in')) {
        return redirect()->to('/admin');
    }

    $questionBankModel = new \App\Models\QuestionBankModel();

    // Get Question Bank
    $questionBank = $questionBankModel->get([
        'questionbank_id' => $id
    ])->getRowArray();

    if (!$questionBank) {
        return redirect()
            ->to('/admin/question_banks')
            ->with('error', 'Question Bank not found');
    }

    // Handle Update
    if ($this->request->getMethod() === 'POST') {

        $parentId = $this->request->getPost('parent_id');

        $data = [
            'questionbank_name' => $this->request->getPost('questionbank_name'),
            'parent_id'         => !empty($parentId) ? $parentId : null,
            'description'       => $this->request->getPost('description')
        ];

        $questionBankModel->edit($data, [
            'questionbank_id' => $id
        ]);

        return redirect()
            ->to('/admin/question_banks')
            ->with('success', 'Question Bank Updated Successfully');
    }

    $data['questionBank'] = $questionBank;

    // Get Other Question Banks
    $data['questionBanks'] = $questionBankModel->get(
        [
            'questionbank_id' => [
                'not_in' => [$id]
            ]
        ],
        '*',
        ['questionbank_name' => 'ASC']
    )->getResultArray();

    return view('admin/edit_question_bank', $data);
}

public function deleteQuestionBank($id)
{
    if (!session()->get('admin_logged_in')) {
        return redirect()->to('/admin');
    }

    $questionBankModel = new \App\Models\QuestionBankModel();

    $questionBank = $questionBankModel->find($id);

    if (!$questionBank) {
        return redirect()
            ->to('/admin/question_banks')
            ->with('error', 'Question Bank not found');
    }

    $hasChildren = $questionBankModel
        ->where('parent_id', $id)
        ->countAllResults();

    $questionModel = new \App\Models\QuestionModel();

    $hasQuestions = $questionModel
        ->where('questionbank_id', $id)
        ->countAllResults();

    if ($hasChildren > 0 || $hasQuestions > 0) {
        return redirect()
            ->to('/admin/question_banks')
            ->with(
                'error',
                'Cannot delete. Question Bank contains child nodes or questions.'
            );
    }
    $questionBankModel->delete($id);

    return redirect()
        ->to('/admin/question_banks')
        ->with(
            'success',
            'Question Bank deleted successfully'
        );
}

//questions
public function questions($questionbankId)
{
    if (!session()->get('admin_logged_in')) {
        return redirect()->to('/admin');
    }

    $questionBankModel = new \App\Models\QuestionBankModel();
    $questionModel = new \App\Models\QuestionModel();

    // Get Question Bank
    $questionBank = $questionBankModel->get([
        'questionbank_id' => $questionbankId
    ])->getRowArray();

    if (!$questionBank) {
        return redirect()
            ->to('/admin/question_banks')
            ->with('error', 'Question Bank not found');
    }

    // Get Questions
    $questions = $questionModel->get(
        [
            'questionbank_id' => $questionbankId
        ],
        '*',
        ['question_id' => 'ASC']
    )->getResultArray();

    return view('admin/questions', [
        'questionBank' => $questionBank,
        'questions'    => $questions
    ]);
}

//add question
public function addQuestion($questionbankId)
{
    if (!session()->get('admin_logged_in')) {
        return redirect()->to('/admin');
    }

    $questionBankModel = new \App\Models\QuestionBankModel();
    $questionModel = new \App\Models\QuestionModel();

    // Get Question Bank
    $questionBank = $questionBankModel->get([
        'questionbank_id' => $questionbankId
    ])->getRowArray();

    if (!$questionBank) {
        return redirect()
            ->to('/admin/question_banks')
            ->with('error', 'Question Bank not found');
    }

    // Handle Form Submission
    if ($this->request->getMethod() === 'POST') {

        $questionModel->add([
            'questionbank_id' => $questionbankId,
            'question_text'   => $this->request->getPost('question_text'),
            'option_a'        => $this->request->getPost('option_a'),
            'option_b'        => $this->request->getPost('option_b'),
            'option_c'        => $this->request->getPost('option_c'),
            'option_d'        => $this->request->getPost('option_d'),
            'correct_answer'  => $this->request->getPost('correct_answer'),
            'explanation'     => $this->request->getPost('explanation'),
        ]);

        return redirect()
            ->to('/admin/questions/' . $questionbankId)
            ->with('success', 'Question added successfully');
    }

    return view('admin/add_question', [
        'questionBank' => $questionBank
    ]);
}

//edit question
public function editQuestion($questionId)
{
    if (!session()->get('admin_logged_in')) {
        return redirect()->to('/admin');
    }

    $questionModel = new \App\Models\QuestionModel();
    $questionBankModel = new \App\Models\QuestionBankModel();

    // Get Question
    $question = $questionModel->get([
        'question_id' => $questionId
    ])->getRowArray();

    if (!$question) {
        return redirect()->back()
            ->with('error', 'Question not found');
    }

    // Get Question Bank
    $questionBank = $questionBankModel->get([
        'questionbank_id' => $question['questionbank_id']
    ])->getRowArray();

    // Update Question
    if ($this->request->getMethod() === 'POST') {

        $questionModel->edit([
            'question_text'  => $this->request->getPost('question_text'),
            'option_a'       => $this->request->getPost('option_a'),
            'option_b'       => $this->request->getPost('option_b'),
            'option_c'       => $this->request->getPost('option_c'),
            'option_d'       => $this->request->getPost('option_d'),
            'correct_answer' => $this->request->getPost('correct_answer'),
            'explanation'    => $this->request->getPost('explanation')
        ], [
            'question_id' => $questionId
        ]);

        return redirect()
            ->to('/admin/questions/' . $question['questionbank_id'])
            ->with('success', 'Question updated successfully');
    }

    return view('admin/edit_question', [
        'question'     => $question,
        'questionBank' => $questionBank
    ]);
}

//delete question
public function deleteQuestion($questionId)
{
    if (!session()->get('admin_logged_in')) {
        return redirect()->to('/admin');
    }

    $questionModel = new \App\Models\QuestionModel();

    $question = $questionModel->find($questionId);

    if (!$question) {
        return redirect()->back()
            ->with('error', 'Question not found');
    }

    $questionBankId = $question['questionbank_id'];

    $questionModel->delete($questionId);

    return redirect()->to(
        '/admin/question_banks/' . $questionBankId
    )->with(
        'success',
        'Question deleted successfully'
    );
}

//view students
public function viewStudents()
{
    if (!session()->get('admin_logged_in')) {
        return redirect()->to('/admin');
    }

    $studentModel = new \App\Models\StudentModel();

    $students = $studentModel->get(
        null,
        '*',
        ['student_id' => 'ASC']
    )->getResultArray();

    return view('admin/view_students', [
        'students' => $students
    ]);
}

//add student
public function addStudent()
{
    if (!session()->get('admin_logged_in')) {
        return redirect()->to('/admin');
    }

    $studentModel = new \App\Models\StudentModel();

    if ($this->request->is('post')) {

        $studentModel->add([

            'name'     => $this->request->getPost('name'),
            'email'    => $this->request->getPost('email'),
            'phone'    => $this->request->getPost('phone'),

            'password' => $studentModel->password_hash(
                $this->request->getPost('password')
            ),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')

        ]);

        return redirect()
            ->to('/admin/view_students')
            ->with(
                'success',
                'Student added successfully'
            );
    }

    return view('admin/add_student');
}

// edit student

public function editStudent($studentId)
{
    if (!session()->get('admin_logged_in')) {
        return redirect()->to('/admin');
    }

    $studentModel = new \App\Models\StudentModel();

    $student = $studentModel->get([
        'student_id' => $studentId
    ])->getRowArray();

    if (!$student) {
        return redirect()
            ->to('/admin/view_students')
            ->with('error', 'Student not found');
    }

    if ($this->request->getMethod() === 'POST') {

        $data = [

            'name'  => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone')

        ];

        // Update password only if entered
        if (!empty($this->request->getPost('password'))) {

            $data['password'] = $studentModel->password_hash(
                $this->request->getPost('password')
            );
        }

        $studentModel->edit(
            $data,
            [
                'student_id' => $studentId
            ]
        );

        return redirect()
            ->to('/admin/view_students')
            ->with(
                'success',
                'Student updated successfully'
            );
    }

    return view('admin/edit_student', [
        'student' => $student
    ]);
}

//delete student
public function deleteStudent($studentId)
{
    if (!session()->get('admin_logged_in')) {
        return redirect()->to('/admin');
    }

    $studentModel = new \App\Models\StudentModel();

    $student = $studentModel->find($studentId);

    if (!$student) {

        return redirect()->to('/admin/view_students')
                         ->with(
                             'error',
                             'Student not found'
                         );
    }

    $studentModel->delete($studentId);

    return redirect()->to('/admin/view_students')
                     ->with(
                         'success',
                         'Student deleted successfully'
                     );
}

//view assigned courses
public function viewStudentCourses()
{
    if (!session()->get('admin_logged_in')) {
        return redirect()->to('/admin');
    }

    $studentCourseModel = new \App\Models\StudentCourseModel();

    $joins = [
        [
            'students',
            'students.student_id = student_courses.student_id',
            'left'
        ],
        [
            'courses',
            'courses.course_id = student_courses.course_id',
            'left'
        ]
    ];

    $assignedCourses = $studentCourseModel->get_join(
        $joins,
        [],
        '
            student_courses.*,
            students.name as student_name,
            students.email,
            courses.course_name
        ',
        ['student_courses.student_course_id' => 'ASC']
    )->getResultArray();

    return view('admin/view_student_courses', [
        'assignedCourses' => $assignedCourses
    ]);
}

//assign course to student
public function assignCourse()
{
    if (!session()->get('admin_logged_in')) {
        return redirect()->to('/admin');
    }

    $studentModel = new \App\Models\StudentModel();
    $courseModel = new \App\Models\CourseModel();
    $studentCourseModel = new \App\Models\StudentCourseModel();

    // Modern CI4 check for POST submissions
    if ($this->request->is('post')) {

        $assignedDate = $this->request->getPost('assigned_date');
        $completionDate = $this->request->getPost('completion_date');

        if (!empty($completionDate) && ($completionDate < $assignedDate)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Completion date cannot be earlier than Assigned date');
        }

        // Prevent duplicate assignment
        $existing = $studentCourseModel
            ->where('student_id', $this->request->getPost('student_id'))
            ->where('course_id', $this->request->getPost('course_id'))
            ->first();

        if ($existing) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'This course is already assigned to this student');
        }

        // Check if insert fails due to model validation rules
        $inserted = $studentCourseModel->insert([
            'student_id'       => $this->request->getPost('student_id'),
            'course_id'        => $this->request->getPost('course_id'),
            'assigned_date'    => $assignedDate,
            'completion_date'  => $completionDate ?: null, // Handle blank dates gracefully
            'progress'         => 0,
            'completed_status' => 0
        ]);

        if (!$inserted) {
            // This will output any validation errors defined directly inside StudentCourseModel
            dd($studentCourseModel->errors());
        }

        return redirect()->to('/admin/view_student_courses')
            ->with('success', 'Course assigned successfully');
    }

    return view('admin/assign_course', [
        'students' => $studentModel->findAll(),
        'courses'  => $courseModel->findAll()
    ]);
}

//edit assigned course
public function editStudentCourse($id)
{
    if (!session()->get('admin_logged_in')) {
        return redirect()->to('/admin');
    }

    $studentCourseModel = new \App\Models\StudentCourseModel();
    $studentModel = new \App\Models\StudentModel();
    $courseModel = new \App\Models\CourseModel();

    $assignment = $studentCourseModel->get([
        'student_course_id' => $id
    ])->getRowArray();

    if (!$assignment) {
        return redirect()
            ->to('/admin/view_student_courses')
            ->with('error', 'Assigned course not found');
    }

    if ($this->request->is('post')) {

        $assignedDate = $this->request->getPost('assigned_date');
        $completionDate = $this->request->getPost('completion_date');

        if (!empty($completionDate) && $completionDate < $assignedDate) {

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Completion date cannot be earlier than Assigned date'
                );
        }

        $studentCourseModel->edit([
            'student_id'        => $this->request->getPost('student_id'),
            'course_id'         => $this->request->getPost('course_id'),
            'assigned_date'     => $assignedDate,
            'completion_date'   => $completionDate,
            'progress'          => $this->request->getPost('progress'),
            'completed_status'  => $this->request->getPost('completed_status')
        ], [
            'student_course_id' => $id
        ]);

        return redirect()
            ->to('/admin/view_student_courses')
            ->with(
                'success',
                'Assigned course updated successfully'
            );
    }

    return view('admin/edit_student_course', [

        'assignment' => $assignment,

        'students' => $studentModel->get(
            null,
            '*',
            ['name' => 'ASC']
        )->getResultArray(),

        'courses' => $courseModel->get(
            null,
            '*',
            ['course_name' => 'ASC']
        )->getResultArray()

    ]);
}

//delete assigned course
public function deleteStudentCourse($id)
{
    if (!session()->get('admin_logged_in')) {
        return redirect()->to('/admin');
    }

    $studentCourseModel = new \App\Models\StudentCourseModel();

    $assignment = $studentCourseModel->find($id);

    if (!$assignment) {

        return redirect()->to('/admin/view_student_courses')
                         ->with(
                             'error',
                             'Assigned course not found'
                         );
    }

    $studentCourseModel->delete($id);

    return redirect()->to('/admin/view_student_courses')
                     ->with(
                         'success',
                         'Assigned course deleted successfully'
                     );
}


//view topic materials page
public function topicMaterials($topicId)
{
    if (!session()->get('admin_logged_in')) {
        return redirect()->to('/admin');
    }

    $topicModel = new \App\Models\TopicModel();
    $materialModel = new \App\Models\TopicMaterialModel();

    $topic = $topicModel->get([
        'topic_id' => $topicId
    ])->getRowArray();

    if (!$topic) {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }

    $materials = $materialModel->get(
        [
            'topic_id' => $topicId
        ],
        '*',
        [
            'material_id' => 'ASC'
        ]
    )->getResultArray();

    return view('admin/topic_materials', [
        'topic'     => $topic,
        'materials' => $materials
    ]);
}


// view the add material page
public function addMaterial($topicId)
{
    if (!session()->get('admin_logged_in')) {
        return redirect()->to('/admin');
    }

    $topicModel = new \App\Models\TopicModel();
    $materialModel = new \App\Models\TopicMaterialModel();

    $topic = $topicModel->get([
        'topic_id' => $topicId
    ])->getRowArray();

    if (!$topic) {
        return redirect()->back();
    }

    if ($this->request->getMethod() === 'POST') {

        $type = $this->request->getPost('material_type');

        $data = [
            'topic_id'       => $topicId,
            'material_title' => $this->request->getPost('material_title'),
            'description'    => $this->request->getPost('description'),
            'material_type'  => $type
        ];

        // YouTube Material
        if ($type === 'youtube') {

            $data['youtube_url'] =
                $this->request->getPost('youtube_url');

            $materialModel->add($data);
        }

        // PDF / Video Material
        else {

            $file = $this->request->getFile('material_file');

            if ($file && $file->isValid() && !$file->hasMoved()) {

                $newName = $file->getRandomName();

                // Folder based on type
                if ($type == 'pdf') {

                    $uploadPath = FCPATH . 'uploads/materials/pdfs/';
                    $dbPath     = 'uploads/materials/pdfs/' . $newName;

                } elseif ($type == 'video') {

                    $uploadPath = FCPATH . 'uploads/materials/videos/';
                    $dbPath     = 'uploads/materials/videos/' . $newName;

                } else {

                    $uploadPath = FCPATH . 'uploads/materials/';
                    $dbPath     = 'uploads/materials/' . $newName;
                }

                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                $file->move($uploadPath, $newName);

                $data['file_path'] = $dbPath;

                $materialModel->add($data);
            }
        }

        session()->setFlashdata(
            'success',
            'Material added successfully.'
        );

        return redirect()->to(
            base_url('admin/topic_materials/' . $topicId)
        );
    }

    return view('admin/add_material', [
        'topic' => $topic
    ]);
}

//edit material page
public function editMaterial($materialId)
{
    if (!session()->get('admin_logged_in')) {
        return redirect()->to('/admin');
    }

    $materialModel = new \App\Models\TopicMaterialModel();
    $topicModel    = new \App\Models\TopicModel();

    $material = $materialModel->get([
        'material_id' => $materialId
    ])->getRowArray();

    if (!$material) {
        return redirect()->back()
            ->with('error', 'Material not found');
    }

    $topic = $topicModel->get([
        'topic_id' => $material['topic_id']
    ])->getRowArray();

    if ($this->request->getMethod() === 'POST') {

        $type = $this->request->getPost('material_type');

        $data = [
            'material_title' => $this->request->getPost('material_title'),
            'description'    => $this->request->getPost('description'),
            'material_type'  => $type
        ];

        if ($type === 'youtube') {

            if (!empty($material['file_path'])) {

                $oldFile = FCPATH . $material['file_path'];

                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }

            $data['youtube_url'] = $this->request->getPost('youtube_url');
            $data['file_path']   = null;

        } else {

            $file = $this->request->getFile('material_file');

            if ($file && $file->isValid() && !$file->hasMoved()) {

                if (!empty($material['file_path'])) {

                    $oldFile = FCPATH . $material['file_path'];

                    if (file_exists($oldFile)) {
                        unlink($oldFile);
                    }
                }

                $newName = $file->getRandomName();

                if ($type == 'pdf') {

                    $uploadPath = FCPATH . 'uploads/materials/pdfs/';
                    $dbPath     = 'uploads/materials/pdfs/' . $newName;

                } elseif ($type == 'video') {

                    $uploadPath = FCPATH . 'uploads/materials/videos/';
                    $dbPath     = 'uploads/materials/videos/' . $newName;

                } else {

                    $uploadPath = FCPATH . 'uploads/materials/';
                    $dbPath     = 'uploads/materials/' . $newName;
                }

                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                $file->move($uploadPath, $newName);

                $data['file_path'] = $dbPath;
            }

            $data['youtube_url'] = null;
        }

        $materialModel->edit($data, [
            'material_id' => $materialId
        ]);

        return redirect()->to(
            '/admin/topic_materials/' . $material['topic_id']
        )->with(
            'success',
            'Material updated successfully.'
        );
    }

    return view('admin/edit_material', [
        'material' => $material,
        'topic'    => $topic
    ]);
}

public function deleteMaterial($materialId)
{
    if (!session()->get('admin_logged_in')) {
        return redirect()->to('/admin');
    }

    $materialModel = new \App\Models\TopicMaterialModel();

    $material = $materialModel->find($materialId);

    if (!$material) {
        return redirect()->back()
            ->with('error', 'Material not found');
    }

    // Store topic id before delete
    $topicId = $material['topic_id'];

    // Delete uploaded file if PDF/MP4
    if (!empty($material['file_path'])) {

        $filePath = FCPATH . $material['file_path'];

        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    // Delete database record
    $materialModel->delete($materialId);

    return redirect()->to(
        base_url('admin/topic_materials/' . $topicId)
    )->with(
        'success',
        'Material deleted successfully.'
    );
}


}
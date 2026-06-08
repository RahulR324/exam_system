<?php

namespace App\Controllers;

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

    $examModel = new \App\Models\ExamModel();
    $studentModel = new \App\Models\StudentModel();
    $courseModel = new \App\Models\CourseModel();
    $questionModel = new \App\Models\QuestionModel();
    $coursecategoryModel = new \App\Models\CourseCategoryModel();

    $data = [

        'examCount' => $examModel->countAllResults(),

        'studentCount' => $studentModel->countAllResults(),

        'courseCount' => $courseModel->countAllResults(),

        'questionCount' => $questionModel->countAllResults(),

        'coursecategorycount' =>$coursecategoryModel->countAllResults(),
        
        'recentStudents' => $studentModel
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

    $db = \Config\Database::connect();

    $search = $this->request->getGet('search');

    $builder = $db->table('course_categories');

    if (!empty($search)) {
        $builder->groupStart()
                ->like('category_name', $search)
                ->orLike('description', $search)
                ->groupEnd();
    }

    $data['categories'] = $builder
                            ->orderBy('category_id', 'ASC')
                            ->get()
                            ->getResultArray();

    $data['search'] = $search;

    return view('admin/course_categories', $data);
}

// manage course category (both add and edit)
public function manageCourseCategory()
{
    // 1. Unified Authentication Check
    if (!session()->get('admin_logged_in')) {
        return redirect()->to('/admin');
    }

    // 2. Check if the form was submitted (POST request)
    if ($this->request->getMethod() === 'POST') {
        
        // Optional but highly recommended: Add validation here
        
        $categoryModel = new \App\Models\CourseCategoryModel();

        $data = [
            'category_name' => $this->request->getPost('category_name'),
            'description'   => $this->request->getPost('description')
        ];

        $categoryModel->insert($data);

        return redirect()
            ->to('/admin/course_categories')
            ->with('success', 'Course category created successfully');
    }

    // 3. Default behavior: Just load the view (GET request)
    return view('admin/add_course_category');
}

// Edit course category
public function editCourseCategory($id)
{
    if (!session()->get('admin_logged_in')) {
        return redirect()->to('/admin');
    }

    $categoryModel = new \App\Models\CourseCategoryModel();

    // Handle form submission
    if ($this->request->getMethod() === 'POST') {

        $data = [
            'category_name' => $this->request->getPost('category_name'),
            'description'   => $this->request->getPost('description')
        ];

        $categoryModel->update($id, $data);

        return redirect()
            ->to('/admin/course_categories')
            ->with('success', 'Course category updated successfully');
    }

    // Load category for edit form
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

    $db = \Config\Database::connect();

    $data['categories'] = $db->table('course_categories')
        ->orderBy('category_name', 'ASC')
        ->get()
        ->getResultArray();

    foreach ($data['categories'] as &$category) {

        $category['courses'] = $db->table('courses')
            ->where('category_id', $category['category_id'])
            ->get()
            ->getResultArray();
    }

    return view('admin/courses', $data);
}

//add course
public function manageCourse()
{
    if (!session()->get('admin_logged_in')) {
        return redirect()->to('/admin');
    }

    $courseModel = new \App\Models\CourseModel();

    $db = \Config\Database::connect();

    // POST
    if ($this->request->getMethod() === 'POST') {

        $thumbnail = $this->request->getFile('thumbnail');

        $thumbnailName = null;

        if ($thumbnail && $thumbnail->isValid()) {

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

        $courseModel->insert($data);

        return redirect()
            ->to('/admin/courses')
            ->with('success', 'Course added successfully');
    }

    // GET

    $data['categories'] = $db->table('course_categories')
        ->orderBy('category_name', 'ASC')
        ->get()
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

    $db = \Config\Database::connect();

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

        $courseModel->update($id, $data);

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

    $data['categories'] = $db->table('course_categories')
        ->orderBy('category_name', 'ASC')
        ->get()
        ->getResultArray();

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

    $db = \Config\Database::connect();

    $selectedCategory = $this->request->getGet('category_id');
    $selectedCourse   = $this->request->getGet('course_id');

    $data['selectedCategory'] = $selectedCategory;
    $data['selectedCourse']   = $selectedCourse;

    // Categories
    $data['categories_filter'] = $db->table('course_categories')
        ->orderBy('category_name', 'ASC')
        ->get()
        ->getResultArray();

    // Courses (filtered by category if selected)
    $courseQuery = $db->table('courses');

    if (!empty($selectedCategory)) {
        $courseQuery->where('category_id', $selectedCategory);
    }

    $data['courses_filter'] = $courseQuery
        ->orderBy('course_name', 'ASC')
        ->get()
        ->getResultArray();

    // Courses for display (same logic)
    $courseQuery2 = $db->table('courses');

    if (!empty($selectedCategory)) {
        $courseQuery2->where('category_id', $selectedCategory);
    }

    if (!empty($selectedCourse)) {
        $courseQuery2->where('course_id', $selectedCourse);
    }

    $data['courses'] = $courseQuery2
        ->orderBy('course_name', 'ASC')
        ->get()
        ->getResultArray();

    foreach ($data['courses'] as &$course) {
        $course['exams'] = $db->table('exams')
            ->where('course_id', $course['course_id'])
            ->orderBy('exam_id', 'DESC')
            ->get()
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

    $db = \Config\Database::connect();
    $courseModel = new \App\Models\CourseModel();

    // GET: Load form
    if ($this->request->getMethod() === 'GET') {

        $data['categories'] = $db->table('course_categories')
            ->orderBy('category_name', 'ASC')
            ->get()
            ->getResultArray();

        $data['courses'] = $courseModel->findAll();

        return view('admin/add_exam', $data);
    }

    // POST: Save exam
    $examModel = new \App\Models\ExamModel();

    $data = [
        'course_id'   => $this->request->getPost('course_id'),
        'title'       => $this->request->getPost('title'),
        'date'        => $this->request->getPost('date'),
        'start_time'  => $this->request->getPost('start_time'),
        'end_time'    => $this->request->getPost('end_time'),
        'duration'    => $this->request->getPost('duration'),
    ];

    $examModel->insert($data);

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

    $db = \Config\Database::connect();
    $examModel = new \App\Models\ExamModel();
    $courseModel = new \App\Models\CourseModel();

    // GET → load edit form
    if ($this->request->getMethod() === 'GET') {

        $data['exam'] = $examModel->find($id);

        if (!$data['exam']) {
            return redirect()
                ->to('/admin/exams')
                ->with('error', 'Exam not found');
        }

        $data['courses'] = $courseModel->findAll();

        return view('admin/edit_exam', $data);
    }

    // POST → update exam
    $updateData = [
        'course_id'   => $this->request->getPost('course_id'),
        'title'       => $this->request->getPost('title'),
        'date'        => $this->request->getPost('date'),
        'start_time'  => $this->request->getPost('start_time'),
        'end_time'    => $this->request->getPost('end_time'),
        'duration'    => $this->request->getPost('duration'),
    ];

    $examModel->update($id, $updateData);

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

    $db = \Config\Database::connect();

    // Get course details
    $data['course'] = $db->table('courses')
        ->where('course_id', $course_id)
        ->get()
        ->getRowArray();

    if (!$data['course']) {
        return redirect()->to('/admin/courses')
            ->with('error', 'Course not found');
    }

    // Get subjects of this course
    $data['subjects'] = $db->table('subjects')
        ->where('course_id', $course_id)
        ->orderBy('subject_name', 'ASC')
        ->get()
        ->getResultArray();

    return view('admin/subjects', $data);
}

//add subject
public function addSubject($course_id)
{
    if (!session()->get('admin_logged_in')) {
        return redirect()->to('/admin');
    }

    $db = \Config\Database::connect();

    // Get course info
    $data['course'] = $db->table('courses')
        ->where('course_id', $course_id)
        ->get()
        ->getRowArray();

    if (!$data['course']) {
        return redirect()->to('/admin/courses')
            ->with('error', 'Course not found');
    }

    // HANDLE POST
    if ($this->request->getMethod() === 'POST') {

        $subjectModel = new \App\Models\SubjectModel();

        $dataInsert = [
            'course_id'     => $course_id,
            'subject_name'  => $this->request->getPost('subject_name'),
            'description'   => $this->request->getPost('description')
        ];

        $subjectModel->insert($dataInsert);

        return redirect()
            ->to('/admin/subjects/'.$course_id)
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

    $db = \Config\Database::connect();

    // Get subject
    $data['subject'] = $db->table('subjects')
        ->where('subject_id', $subject_id)
        ->get()
        ->getRowArray();

    if (!$data['subject']) {
        return redirect()->to('/admin/courses')
            ->with('error', 'Subject not found');
    }

    // Get course (for back button + context)
    $data['course'] = $db->table('courses')
        ->where('course_id', $data['subject']['course_id'])
        ->get()
        ->getRowArray();

    // POST update
    if ($this->request->getMethod() === 'POST') {

        $updateData = [
            'subject_name' => $this->request->getPost('subject_name'),
            'description'  => $this->request->getPost('description'),
        ];

        $db->table('subjects')
            ->where('subject_id', $subject_id)
            ->update($updateData);

        return redirect()->to('admin/subjects/' . $data['subject']['course_id'])
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

    $db = \Config\Database::connect();

    // Get subject details
    $data['subject'] = $db->table('subjects')
        ->where('subject_id', $subject_id)
        ->get()
        ->getRowArray();

    if (!$data['subject']) {
        return redirect()->to('/admin/courses')
            ->with('error', 'Subject not found');
    }

    // Get course (for breadcrumb / UI)
    $data['course'] = $db->table('courses')
        ->where('course_id', $data['subject']['course_id'])
        ->get()
        ->getRowArray();

    // Get topics
    $data['topics'] = $db->table('topics')
    ->select('topics.*, COUNT(topic_materials.material_id) as material_count')
    ->join(
        'topic_materials',
        'topic_materials.topic_id = topics.topic_id',
        'left'
    )
    ->where('topics.subject_id', $subject_id)
    ->groupBy('topics.topic_id')
    ->orderBy('topics.topic_id', 'ASC')
    ->get()
    ->getResultArray();

    return view('admin/topics', $data);
}

//add topic
public function addTopic($subjectId)
{
    if (!session()->get('admin_logged_in')) {
        return redirect()->to('/admin');
    }

    $db = \Config\Database::connect();

    // Get subject + course info
    $data['subject'] = $db->table('subjects')
        ->where('subject_id', $subjectId)
        ->get()
        ->getRowArray();

    if (!$data['subject']) {
        return redirect()->to('/admin/courses')
            ->with('error', 'Subject not found');
    }

    $courseId = $data['subject']['course_id'];

    // Handle POST
    if ($this->request->getMethod() === 'POST') {

        $topicModel = new \App\Models\TopicModel();

        $topicModel->save([
            'subject_id'   => $subjectId,
            'topic_name'   => $this->request->getPost('topic_name'),
            'description'  => $this->request->getPost('description'),
        ]);

        return redirect()->to('admin/topics/' . $subjectId)
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

    $topic = $topicModel->find($id);

    if (!$topic) {
        return redirect()->to('/admin/courses')
                         ->with('error', 'Topic not found');
    }

    // ✅ FIXED POST CHECK (IMPORTANT)
    if ($this->request->getMethod(true) === 'POST') {

        $data = [
            'topic_name'  => $this->request->getPost('topic_name'),
            'description' => $this->request->getPost('description'),
        ];

        $topicModel->update($id, $data);

        return redirect()->to('/admin/topics/' . $topic['subject_id'])
                         ->with('success', 'Topic updated successfully');
    }

    return view('admin/edit_topic', [
        'topic' => $topic
    ]);
}

//question bank page
public function questionBanks($parentId = null)
{
    $bankModel = new \App\Models\QuestionBankModel();
    $questionModel = new \App\Models\QuestionModel();

    $search = trim($this->request->getGet('search'));

    // SEARCH MODE
    if (!empty($search)) {

        $questions = $questionModel
            ->select('questions.*, question_banks.questionbank_name')
            ->join(
                'question_banks',
                'question_banks.questionbank_id = questions.questionbank_id',
                'left'
            )
            ->groupStart()
                ->like('question_text', $search)
                ->orLike('option_a', $search)
                ->orLike('option_b', $search)
                ->orLike('option_c', $search)
                ->orLike('option_d', $search)
            ->groupEnd()
            ->findAll();

        return view('admin/question_banks', [
            'banks'      => [],
            'questions'  => $questions,
            'parentId'   => null,
            'path'       => [],
            'search'     => $search,
            'isSearch'   => true
        ]);
    }

    // NORMAL MODE
    $banks = $bankModel->where('parent_id', $parentId)->findAll();

    $questions = [];

    if ($parentId) {
        $questions = $questionModel
            ->where('questionbank_id', $parentId)
            ->findAll();
    }

    // Breadcrumb Path
    $path = [];
    $tempId = $parentId;

    while ($tempId) {

        $node = $bankModel->find($tempId);

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

    if ($this->request->is('post')) {

        $parentId = $this->request->getPost('parent_id');

        $questionBankModel->insert([
            'questionbank_name' => $this->request->getPost('questionbank_name'),
            'parent_id'         => !empty($parentId) ? $parentId : null,
            'description'       => $this->request->getPost('description')
        ]);

        return redirect()
            ->to('/admin/question_banks')
            ->with('success', 'Question Bank Added Successfully');
    }

    $data['questionBanks'] = $questionBankModel
                                ->orderBy('questionbank_name', 'ASC')
                                ->findAll();

    return view('admin/add_question_bank', $data);
}

//edit question bank
public function editQuestionBank($id)
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

    if ($this->request->is('post')) {

        $parentId = $this->request->getPost('parent_id');

        $questionBankModel->update($id, [
            'questionbank_name' => $this->request->getPost('questionbank_name'),
            'parent_id'         => !empty($parentId) ? $parentId : null,
            'description'       => $this->request->getPost('description')
        ]);

        return redirect()
            ->to('/admin/question_banks')
            ->with('success', 'Question Bank Updated Successfully');
    }

    $data['questionBank'] = $questionBank;

    $data['questionBanks'] = $questionBankModel
        ->where('questionbank_id !=', $id)
        ->orderBy('questionbank_name', 'ASC')
        ->findAll();

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
    $questionBankModel = new \App\Models\QuestionBankModel();
    $questionModel = new \App\Models\QuestionModel();

    $questionBank = $questionBankModel->find($questionbankId);

    $questions = $questionModel
        ->where('questionbank_id', $questionbankId)
        ->findAll();

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

    $questionBank = $questionBankModel->find($questionbankId);

    if (!$questionBank) {
        return redirect()->to('/admin/question_banks')
            ->with('error', 'Question Bank not found');
    }

    if ($this->request->getMethod(true) === 'POST') {

        $questionModel->insert([
            'questionbank_id' => $questionbankId,
            'question_text'   => $this->request->getPost('question_text'),
            'option_a'        => $this->request->getPost('option_a'),
            'option_b'        => $this->request->getPost('option_b'),
            'option_c'        => $this->request->getPost('option_c'),
            'option_d'        => $this->request->getPost('option_d'),
            'correct_answer'  => $this->request->getPost('correct_answer'),
            'explanation'     => $this->request->getPost('explanation'),
        ]);

        return redirect()->to('/admin/questions/' . $questionbankId)
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

    $question = $questionModel->find($questionId);

    if (!$question) {
        return redirect()->back()
            ->with('error', 'Question not found');
    }

    $questionBank = $questionBankModel
        ->find($question['questionbank_id']);

    if ($this->request->is('post')) {

    $questionModel->update($questionId, [
        'question_text'  => $this->request->getPost('question_text'),
        'option_a'       => $this->request->getPost('option_a'),
        'option_b'       => $this->request->getPost('option_b'),
        'option_c'       => $this->request->getPost('option_c'),
        'option_d'       => $this->request->getPost('option_d'),
        'correct_answer' => $this->request->getPost('correct_answer'),
        'explanation'    => $this->request->getPost('explanation')
    ]);

    return redirect()->to('/admin/question_banks/' . $question['questionbank_id'])
                     ->with('success', 'Question updated successfully');
}

    return view('admin/edit_question', [

        'question'      => $question,
        'questionBank'  => $questionBank

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

    $students = $studentModel
        ->orderBy('student_id', 'ASC')
        ->findAll();

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

        $studentModel->insert([

            'name'     => $this->request->getPost('name'),
            'email'    => $this->request->getPost('email'),
            'phone'    => $this->request->getPost('phone'),

            // password encrypted
            'password' => password_hash(
                $this->request->getPost('password'),
                PASSWORD_DEFAULT
            )

        ]);

        return redirect()->to('/admin/view_students')
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

    $student = $studentModel->find($studentId);

    if (!$student) {
        return redirect()->to('/admin/view_students')
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

            $data['password'] = password_hash(
                $this->request->getPost('password'),
                PASSWORD_DEFAULT
            );
        }

        $studentModel->update($studentId, $data);

        return redirect()->to('/admin/view_students')
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

    $db = \Config\Database::connect();

    $assignedCourses = $db->table('student_courses sc')
        ->select('
            sc.*,
            s.name as student_name,
            s.email,
            c.course_name
        ')
        ->join('students s', 's.student_id = sc.student_id')
        ->join('courses c', 'c.course_id = sc.course_id')
        ->orderBy('sc.student_course_id', 'ASC')
        ->get()
        ->getResultArray();

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

    $assignment = $studentCourseModel->find($id);

    if (!$assignment) {

        return redirect()->to('/admin/view_student_courses')
                         ->with(
                             'error',
                             'Assigned course not found'
                         );
    }

    if ($this->request->is('post')) {

        $assignedDate = $this->request->getPost('assigned_date');
        $completionDate = $this->request->getPost('completion_date');

        if (!empty($completionDate) && $completionDate < $assignedDate) {

            return redirect()->back()
                             ->withInput()
                             ->with(
                                 'error',
                                 'Completion date cannot be earlier than Assigned date'
                             );
        }

        $studentCourseModel->update($id, [

            'student_id' => $this->request->getPost('student_id'),

            'course_id' => $this->request->getPost('course_id'),

            'assigned_date' => $assignedDate,

            'completion_date' => $completionDate,

            'progress' => $this->request->getPost('progress'),

            'completed_status' => $this->request->getPost('completed_status')

        ]);

        return redirect()->to('/admin/view_student_courses')
                         ->with(
                             'success',
                             'Assigned course updated successfully'
                         );
    }

    return view('admin/edit_student_course', [

        'assignment' => $assignment,

        'students' => $studentModel->findAll(),

        'courses' => $courseModel->findAll()

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
    $topicModel = new \App\Models\TopicModel();
    $materialModel = new \App\Models\TopicMaterialModel();

    $topic = $topicModel->find($topicId);

    if (!$topic) {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }

    $materials = $materialModel
        ->where('topic_id', $topicId)
        ->findAll();

    return view('admin/topic_materials', [
        'topic'     => $topic,
        'materials' => $materials
    ]);
}


// view the add material page
public function addMaterial($topicId)
{
    $topicModel = new \App\Models\TopicModel();
    $materialModel = new \App\Models\TopicMaterialModel();

    $topic = $topicModel->find($topicId);

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

        // YouTube
        if ($type === 'youtube') {

            $data['youtube_url'] =
                $this->request->getPost('youtube_url');

            $materialModel->insert($data);
        }

        // PDF / MP4
        else {

        $file = $this->request->getFile('material_file');

        if ($file && $file->isValid() && !$file->hasMoved()) {

        $newName = $file->getRandomName();

        // Decide folder based on material type
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

        // Create folder if not exists
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $file->move($uploadPath, $newName);

        $data['file_path'] = $dbPath;

        $materialModel->insert($data);
    }
    }

        session()->setFlashdata(
            'success',
            'Material added successfully.'
        );

        return redirect()->to(
            base_url('admin/topic_materials/'.$topicId)
        );
    }

    return view('admin/add_material', [
        'topic' => $topic
    ]);
}

//edit material page
public function editMaterial($materialId)
{
    $materialModel = new \App\Models\TopicMaterialModel();
    $topicModel    = new \App\Models\TopicModel();

    $material = $materialModel->find($materialId);

    if (!$material) {
        return redirect()->back();
    }

    $topic = $topicModel->find($material['topic_id']);

    if ($this->request->getMethod() === 'POST') {

        $type = $this->request->getPost('material_type');

        $data = [
            'material_title' => $this->request->getPost('material_title'),
            'description'    => $this->request->getPost('description'),
            'material_type'  => $type
        ];

        /*
        |--------------------------------------------------------------------------
        | YOUTUBE
        |--------------------------------------------------------------------------
        */
        if ($type === 'youtube') {

            // Delete old uploaded file if exists
            if (!empty($material['file_path'])) {

                $oldFile = FCPATH . $material['file_path'];

                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }

            $data['youtube_url'] =
                $this->request->getPost('youtube_url');

            $data['file_path'] = null;
        }

        /*
        |--------------------------------------------------------------------------
        | PDF / VIDEO
        |--------------------------------------------------------------------------
        */
        else {

            $file = $this->request->getFile('material_file');

            if ($file && $file->isValid() && !$file->hasMoved()) {

                // Delete old file if exists
                if (!empty($material['file_path'])) {

                    $oldFile = FCPATH . $material['file_path'];

                    if (file_exists($oldFile)) {
                        unlink($oldFile);
                    }
                }

                $newName = $file->getRandomName();

                if ($type == 'pdf') {

                    $uploadPath =
                        FCPATH . 'uploads/materials/pdfs/';

                    $dbPath =
                        'uploads/materials/pdfs/' . $newName;

                } elseif ($type == 'video') {

                    $uploadPath =
                        FCPATH . 'uploads/materials/videos/';

                    $dbPath =
                        'uploads/materials/videos/' . $newName;

                } else {

                    $uploadPath =
                        FCPATH . 'uploads/materials/';

                    $dbPath =
                        'uploads/materials/' . $newName;
                }

                // Create folder if missing
                if (!is_dir($uploadPath)) {

                    mkdir(
                        $uploadPath,
                        0777,
                        true
                    );
                }

                $file->move(
                    $uploadPath,
                    $newName
                );

                $data['file_path'] = $dbPath;
            }

            $data['youtube_url'] = null;
        }

        $materialModel->update(
            $materialId,
            $data
        );

        return redirect()->to(
            base_url(
                'admin/topic_materials/' .
                $material['topic_id']
            )
        )->with(
            'success',
            'Material updated successfully.'
        );
    }

    return view(
        'admin/edit_material',
        [
            'material' => $material,
            'topic'    => $topic
        ]
    );
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
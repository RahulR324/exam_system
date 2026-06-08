<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


/*
|--------------------------------------------------------------------------
| STUDENT ROUTES
|--------------------------------------------------------------------------
*/

$routes->get('/', 'AuthController::index');

$routes->post('login', 'AuthController::login');

$routes->get(
    'instructions',
    'AuthController::instructions'
);

$routes->match(
    ['get', 'post'],
    'exam',
    'AuthController::exam'
);

$routes->get(
    'result',
    'AuthController::result'
);

$routes->get(
    'getExamData',
    'AuthController::getExamData'
);

$routes->get(
    'getResultData',
    'AuthController::getResultData'
);

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

$routes->get(
    'admin',
    'AdminController::loginPage'
);

$routes->post(
    'admin/login',
    'AdminController::login'
);

$routes->get(
    'admin/logout',
    'AdminController::logout'
);

$routes->get(
    'admin/dashboard',
    'AdminController::dashboard'
);

$routes->get(
    'admin/course_categories', 
    'AdminController::courseCategories'
);

$routes->get(
    'admin/add_course_category', 
    'AdminController::manageCourseCategory'
);
$routes->post(
    'admin/add_course_category/create', 
    'AdminController::manageCourseCategory'
);

$routes->get(
    'admin/edit_course_category/(:num)', 
    'AdminController::editCourseCategory/$1'
);

$routes->post(
    'admin/edit_course_category/(:num)', 
    'AdminController::editCourseCategory/$1'
);

$routes->get(
    'admin/delete_course_category/(:num)', 
    'AdminController::deleteCourseCategory/$1'
);

$routes->get(
    'admin/courses',
    'AdminController::courses'
);

$routes->get(
    'admin/add_course',
    'AdminController::manageCourse'
);
$routes->post(
    'admin/add_course',
    'AdminController::manageCourse'
);

$routes->get(
    'admin/edit_course/(:num)',
    'AdminController::editCourse/$1'
);
$routes->post(
    'admin/edit_course/(:num)',
    'AdminController::editCourse/$1'
);
$routes->get(
    'admin/delete_course/(:num)',
    'AdminController::deleteCourse/$1'
);

$routes->get(
    'admin/exams',
    'AdminController::exams'
);

$routes->get(
    'admin/delete_exam/(:num)',
    'AdminController::deleteExam/$1'
);

$routes->get(
    'admin/add_exam',
    'AdminController::manageExam'
);
$routes->post(
    'admin/add_exam',
    'AdminController::manageExam'
);

$routes->get(
    'admin/edit_exam/(:num)',
    'AdminController::manageEditExam/$1'
);
$routes->post(
    'admin/edit_exam/(:num)',
    'AdminController::manageEditExam/$1'
);

$routes->get(
    'admin/subjects/(:num)', 
    'AdminController::subjects/$1'
);

$routes->get(
    'admin/add_subject/(:num)', 
    'AdminController::addSubject/$1'
);
$routes->post(
    'admin/add_subject/(:num)', 
    'AdminController::addSubject/$1'
);

$routes->get(
    'admin/delete_subject/(:num)', 
    'AdminController::deleteSubject/$1'
);

$routes->get(
    'admin/edit_subject/(:num)', 
    'AdminController::editSubject/$1'
);
$routes->post(
    'admin/edit_subject/(:num)', 
    'AdminController::editSubject/$1'
);

$routes->get(
    'admin/topics/(:num)', 
    'AdminController::topics/$1'
);

$routes->get(
    'admin/add_topic/(:num)', 
    'AdminController::addTopic/$1'
);
$routes->post(
    'admin/add_topic/(:num)', 
    'AdminController::addTopic/$1'
);

$routes->get(
    'admin/delete_topic/(:num)', 
    'AdminController::deleteTopic/$1'
);

$routes->get(
    'admin/edit_topic/(:num)', 
    'AdminController::editTopic/$1'
);
$routes->post(
    'admin/edit_topic/(:num)', 
    'AdminController::editTopic/$1'
);

// Question Bank
$routes->get(
    'admin/question_banks', 
    'AdminController::questionBanks'
);
$routes->get(
    'admin/question_banks/(:num)', 
    'AdminController::questionBanks/$1'
);


$routes->match(
    ['get', 'post'],
    'admin/add_question_bank',
    'AdminController::addQuestionBank'
);

$routes->match(
    ['get', 'post'],
    'admin/edit_question_bank/(:num)',
    'AdminController::editQuestionBank/$1'
);

$routes->get(
    'admin/delete_question_bank/(:num)',
    'AdminController::deleteQuestionBank/$1'
);

$routes->get(
    'admin/questions/(:num)',
    'AdminController::questions/$1'
);

$routes->match(
    ['get','post'], 
    'admin/add_question/(:num)', 
    'AdminController::addQuestion/$1'
);

$routes->match(
    ['get','post'],
    'admin/edit_question/(:num)',
    'AdminController::editQuestion/$1'
);

$routes->get(
    'admin/delete_question/(:num)',
    'AdminController::deleteQuestion/$1'
);

$routes->get(
    'admin/view_students',
    'AdminController::viewStudents'
);

$routes->match(
    ['get','post'],
    'admin/add_student',
    'AdminController::addStudent'
);

$routes->match(
    ['get','post'],
    'admin/edit_student/(:num)',
    'AdminController::editStudent/$1'
);

$routes->get(
    'admin/delete_student/(:num)',
    'AdminController::deleteStudent/$1'
);

$routes->get(
    'admin/view_student_courses',
    'AdminController::viewStudentCourses'
);

$routes->match(
    ['get', 'post'],
    'admin/assign_course',
    'AdminController::assignCourse'
);

$routes->match(
    ['get', 'post'],
    'admin/edit_student_course/(:num)',
    'AdminController::editStudentCourse/$1'
);


$routes->get(
    'admin/delete_student_course/(:num)',
    'AdminController::deleteStudentCourse/$1'
);


$routes->get(
    'admin/topic_materials/(:num)',
    'AdminController::topicMaterials/$1'
);


$routes->match(['get','post'],
    'admin/add_material/(:num)',
    'AdminController::addMaterial/$1'
);

$routes->match(
    ['get','post'],
    'admin/edit_material/(:num)',
    'AdminController::editMaterial/$1'
);

$routes->get(
    'admin/delete_material/(:num)',
    'AdminController::deleteMaterial/$1'
);











// QUESTIONS MANAGEMENT
$routes->get('admin/questions/(:num)', 'AdminController::questions/$1');

$routes->get('admin/edit-question/(:num)', 'AdminController::editQuestion/$1');

$routes->post('admin/update-question/(:num)', 'AdminController::updateQuestion/$1');

$routes->match(['get', 'post'], 'admin/view_students', 'AdminController::studentsList');
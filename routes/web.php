<?php

use Illuminate\Http\Request;
use App\Http\Middleware\AdminAuth;
use App\Http\Middleware\StudentAuth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CourseGroups;

use App\Http\Controllers\DashboardPage;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\GroupsController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ApplicationsController;

use App\Http\Controllers\GroupDetailsController;
use App\Http\Controllers\CourseDetailsController;

//--------------------------------------------- Studnets Protect routes
Route::middleware([StudentAuth::class])->group(function () {
    Route::get('/student/courses/section', [StudentController::class, 'index']);
    Route::get('/groups', [StudentController::class, 'getGroups']);
    Route::get('/student/courses/apply/{id}', [StudentController::class, 'courseapplyPage']);
    Route::post('/student/apply', [StudentController::class, 'applyCourse']);
    Route::get('/student/enrolled/courses', [StudentController::class, 'viewEnrolledCoursesPage'])->name('student.enrolled.courses.page');
    Route::get('/student/get-enrolled-courses', [StudentController::class, 'getEnrolledCourses']);
    Route::post('/enroll', [StudentController::class, 'enroll']);
    Route::get('/courses/filter', [CourseDetailsController::class, 'filter'])->name('filter.courses');
    
});



// ----------------------------------Student
Route::get('/student/Registraion/Page', [StudentController::class, 'studentRegisterPage']);
Route::get('/', [StudentController::class, 'studentLoginPage']);
Route::get('/student/Forget/Id/Page', [StudentController::class, 'forgetIdPage']);
Route::post('/student/register', [StudentController::class, 'registerStudent']);
Route::post('/student/login', [StudentController::class, 'login']);
Route::post('/FindUniqueId', [StudentController::class, 'findUniqueId']);


Route::get('/student/logout', function (Request $request) {
    $request->session()->forget('student_id');
    return redirect('/');
});
Route::get('/admin/logout', function (Request $request) {
    $request->session()->forget('admin_id');
    return redirect('/Admin');
});
    Route::get('/Get/Course/Details/{id}', [CourseController::class, 'getCompleteCourseDetails'])->name('CourseDetailsById');



// --------------------------------------------------Admin Routes
Route::middleware([AdminAuth::class])->group(function () {
    Route::get('/Admin/Dashboard', [DashboardPage::class, 'dashboardPage'])->name('StartPage');
    // ------------------------Course Group
    Route::get('/Create/Course/Group/Page', [CourseGroups::class, 'createCourseGroupPage'])->name('CourseGroupPage');
    Route::get('/Course/Group/List/Page', [CourseGroups::class, 'courseGroupList'])->name('CourseGroupListPage');
    Route::post('/Create/Course/Group', [CourseGroups::class, 'store'])->name('CreateCourseGroup');
    Route::get('/Course/Groups/List', [CourseGroups::class, 'list'])->name('CourseGroupList');
    Route::get('/Course/Groups/Active/List', [CourseGroups::class, 'activeList'])->name('CourseGroupList');
    Route::get('/Course/Group/Select/{id}', [CourseGroups::class, 'select'])->name('GroupLists');
    Route::post('/Course/Group/Edit/{id}', [CourseGroups::class, 'update'])->name('GroupEdit');
    Route::delete('/Course/Group/delete/{id}', [CourseGroups::class, 'destroy']);
    Route::patch('/Course/Group/StatusChange/{id}', [CourseGroups::class, 'statusChange'])->name('statusChange');


    //-------------------------Course Controller
    Route::get('/Create/Course/Page', [CourseController::class, 'createCoursePage'])->name('CoursePage');
    Route::get('/Edit/Course/Page/{id}', [CourseController::class, 'editCoursePage'])->name('editCoursePage');
    Route::get('/Course/List/Page', [CourseController::class, 'courseListPage'])->name('CourseListPage');
    Route::get('/Course/Edit/Page', [CourseController::class, 'courseEditPage'])->name('CourseEditPage');
    Route::get('/Viwe/Course/Details/Page/{id}', [CourseController::class, 'coursViewPage'])->name('ViweCoursePage');
    Route::post('/Create/Course', [CourseController::class, 'store'])->name('CreateCourse');
    Route::get('/Course/List', [CourseController::class, 'list'])->name('CourseList');
    Route::get('/Select/Course/{id}', [CourseController::class, 'select'])->name('selectCourse');
    Route::put('/Update/Course/{id}', [CourseController::class, 'update'])->name('updateCourseDetails');
    Route::delete('/Delete/Course/{id}', [CourseController::class, 'destroy'])->name('destroyCourseDetails');
    Route::patch('/Course/StatusChange/{id}', [CourseController::class, 'statusChange'])->name('CoursestatusChange');



    //    -------------------------- Course Details Controller
    Route::get('/getGroupDetails/{g_id}', [CourseDetailsController::class, 'getGroupDetails']);
    Route::get('/Add/Course/Details/Page/{id}', [CourseDetailsController::class, 'addCourseDetailsPage'])->name('AddCoursePage');
    Route::post('/storeCourseDetails', [CourseDetailsController::class, 'addDetails'])->name('CreateCourseDetails');
    Route::get('/Course/Details/List', [CourseDetailsController::class, 'list'])->name('CourseDetailsList');

    // --------------------------Groups
    Route::get('/Create/Groups/Page', [GroupsController::class, 'createGroupPage'])->name('GroupPage');
    Route::get('/Groups/List/Page', [GroupsController::class, 'groupListPage'])->name('GroupListPage');
    Route::post('/Create/Group', [GroupsController::class, 'store'])->name('CreateCourse');
    Route::get('/Group/List', [GroupsController::class, 'list'])->name('GroupLists');
    Route::get('/Group/Select/{id}', [GroupsController::class, 'show'])->name('GroupLists');
    Route::post('/Group/Edit/{id}', [GroupsController::class, 'update'])->name('GroupEdit');
    Route::patch('/Group/StatusChange/{id}', [GroupsController::class, 'statusChange'])->name('statusChange');
    Route::delete('/Group/delete/{id}', [GroupsController::class, 'destroy']);


    // Group details
    Route::get('/Add/Groups/Details/Page/{g_id}', [GroupDetailsController::class, 'addDetailsGroupPage'])->name('DetailsGroupPage');
    Route::get('/Viwe/Group/Details/Page/{g_id}', [GroupDetailsController::class, 'viewDetailsGroupPage'])->name('ViewGroupDetailsPage');
    Route::post('/Create/Groups/Details', [GroupDetailsController::class, 'store'])->name('CreateCourse');
    Route::get('/View/Groups/Details/{id}', [GroupDetailsController::class, 'viewDetails'])->name('viewDetails');

    Route::get('/Group/Details/List/{id}', [GroupDetailsController::class, 'idWiseDetailsList'])->name('IdWiseDetailsList');
    Route::get('/Group/Details/Select/{id}', [GroupDetailsController::class, 'select'])->name('IdWiseDetailsList');
    Route::patch('/Group/Details/StatusChange/{id}', [GroupDetailsController::class, 'statusChange'])->name('IdWiseDetailsList');
    Route::post('/Group/Details/Edit/{id}', [GroupDetailsController::class, 'update'])->name('GroupEdit');
    Route::delete('/Group/Details/delete/{id}', [GroupDetailsController::class, 'destroy']);

    // ---------------------------------------------Applications
    Route::get('/Pending/Applications/Page', [ApplicationsController::class, 'pendingApplicationsPage'])->name('PendingApplicationsPage');
    Route::get('/Rejected/Applications/Page', [ApplicationsController::class, 'rejectedApplicationsPage'])->name('RejectedApplicationsPage');
    Route::get('/Accepted/Applications/Page', [ApplicationsController::class, 'acceptedApplicationsPage'])->name('ApprovedApplicationsPage');
    Route::get('/Viwe/Application/Page/{id}', [ApplicationsController::class, 'viewApplicationPage'])->name('viewApplicationPage');

    Route::get('/Viwe/Application/{id}', [ApplicationsController::class, 'viewApplications'])->name('viewApplication');

    Route::get('/Pending/Applications/List', [ApplicationsController::class, 'pendingApplicationsList'])->name('AcceptedApplications');
    Route::get('/Rejected/Applications/List', [ApplicationsController::class, 'rejectedApplicationsList'])->name('AcceptedApplications');
    Route::get('/Approved/Applications/List', [ApplicationsController::class, 'approvedApplicationsList'])->name('ApprovedApplications');
    Route::patch('/Application/StatusChange/{id}', [ApplicationsController::class, 'statusChange'])->name('ApplicationstatusChange');
    Route::get('/Count/Applications', [ApplicationsController::class, 'countApplication'])->name('CountApplications');
});

Route::get('/Admin/Register/Page', [DashboardPage::class, 'adminRegisterPage'])->name('adminRegisterPage');
Route::get('/Admin', [DashboardPage::class, 'adminLoginPage'])->name('adminRegisterPage');
Route::post('/Admin/Register', [DashboardPage::class, 'registerAdmin'])->name('registerAdmin');
Route::post('/Admin/Login', [DashboardPage::class, 'loginAdmin'])->name('loginAdmin');

<?php

use App\Http\Controllers\FormsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CollegeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\CenterController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ExportCourseController;
use App\Http\Controllers\GroupsController;
use App\Http\Controllers\CommitteController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\WorkflowController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\FrequentUsedController;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\authentications\RegisterBasic;

use App\Http\Controllers\PdfController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::middleware(['auth'])->group(function () {
    // Main Page Route
    Route::get('/', [LoginBasic::class, 'emptyPage'])->name('empty-page');
    Route::get('/currnt_user', [UserController::class, 'get_current_user'])->name('user-current-get');

    // Users UI Routes
    Route::group(['middleware' => ['role_or_permission:super-admin|users_view']], function () {
        Route::get('/users', [UserController::class, 'index'])->name('users-list');
        Route::post('/users', [UserController::class, 'get_users'])->name('users-get');
    });
    Route::group(['middleware' => ['role_or_permission:super-admin|users_add']], function () {
        Route::get('/users/add', [UserController::class, 'addForm'])->name('user-add');
        Route::post('/user/add', [UserController::class, 'createUser'])->name('user-create');
    });
    Route::group(['middleware' => ['role_or_permission:super-admin|users_edit|users_view']], function () {
        Route::get('/user/{id}', [UserController::class, 'get_user'])->name('user-get');
    });
    Route::group(['middleware' => ['role_or_permission:super-admin|users_edit']], function () {
        Route::post('/user/edit', [UserController::class, 'edit_user'])->name('user-edit');
    });
    Route::group(['middleware' => ['role_or_permission:super-admin|users_delete']], function () {
        Route::post('/user/delete', [UserController::class, 'delete'])->name('user-delete');
    });

    // Groups routes
    Route::group(['middleware' => ['role_or_permission:super-admin|groups_add']], function () {
        Route::get('/add/users/groups', [GroupsController::class, 'createUserGroup'])->name('user-create-group');
        Route::post('/add/users/groups', [GroupsController::class, 'addUsersGroup'])->name('user-add-group');
    });
    Route::group(['middleware' => ['role_or_permission:super-admin|groups_edit|groups_view']], function () {
        Route::post('/get/group/{id}', [GroupsController::class, 'get_group'])->name('get-group-group');
        Route::post('/get/group_affiliation', [GroupsController::class, 'get_affiliations'])->name('get-group-affiliation');
        Route::post('/users/groups', [GroupsController::class, 'get_groups'])->name('get-groups');
        Route::post('/groups/users', [GroupsController::class, 'get_groups_members'])->name('groups-members');
    });
    Route::group(['middleware' => ['role_or_permission:super-admin|groups_edit']], function () {
        Route::get('/edit/users/groups/{id}', [GroupsController::class, 'editUsersGroup'])->name('user-edit-group');
        Route::post('/edit/users/groups', [GroupsController::class, 'updateUsersGroup']);
        Route::post('/user/groups/edit/permissions', [GroupsController::class, 'edit_groups_permissions'])->name('user-groups-permissions');
    });
    Route::group(['middleware' => ['role_or_permission:super-admin|groups_delete']], function () {
        Route::post('delete/users/groups', [GroupsController::class, 'delete'])->name('groups-delete');
    });

    // Committees routes
    Route::group(['middleware' => ['role_or_permission:super-admin|committees_view']], function () {
        Route::get('/committees', [CommitteController::class, 'index'])->name('committees-list');
    });
    Route::group(['middleware' => ['role_or_permission:super-admin|committees_add']], function () {
        Route::get('/committee/add', [CommitteController::class, 'add'])->name('committees-add');
        Route::post('/committee/add', [CommitteController::class, 'addCom'])->name('committees-addCom');

    });
    Route::group(['middleware' => ['role_or_permission:super-admin|committees_edit|committees_view']], function () {
        Route::post('/committees', [CommitteController::class, 'get_committees'])->name('committees-get');
        Route::get('/committee/{id}', [CommitteController::class, 'get_committee'])->name('committee-get');
    });
    Route::group(['middleware' => ['role_or_permission:super-admin|committees_edit']], function () {
        Route::post('/committee/edit', [CommitteController::class, 'edit_committee'])->name('committee-edit');
    });
    Route::group(['middleware' => ['role_or_permission:super-admin|committees_delete']], function () {
        Route::post('/committee/delete', [CommitteController::class, 'delete'])->name('committee-delete');
    });

    // Offices UI Routes
    Route::group(['middleware' => ['role_or_permission:super-admin|offices_view']], function () {
        Route::get('/offices', [OfficeController::class, 'index'])->name('offices-list');
        Route::post('/offices', [OfficeController::class, 'get'])->name('offices-get');
    });
    Route::group(['middleware' => ['role_or_permission:super-admin|offices_add']], function () {
        Route::get('/office/add', [OfficeController::class, 'create'])->name('offices-create');
        Route::post('/office/add', [OfficeController::class, 'add'])->name('offices-add');
    });
    Route::group(['middleware' => ['role_or_permission:super-admin|offices_edit|offices_view']], function () {
        Route::get('/office/{id}', [OfficeController::class, 'office'])->name('office-get');
    });
    Route::group(['middleware' => ['role_or_permission:super-admin|offices_edit']], function () {
        Route::post('/office/edit', [OfficeController::class, 'edit'])->name('office-edit');
    });
    Route::group(['middleware' => ['role_or_permission:super-admin|offices_delete']], function () {
        Route::post('/office/delete', [OfficeController::class, 'delete'])->name('office-delete');
    });
    // END Offices UI Routes

    Route::group(['middleware' => ['role_or_permission:super-admin|colleges_view']], function () {
        Route::get('/colleges', [CollegeController::class, 'index'])->name('colleges-list');
        Route::post('/colleges', [CollegeController::class, 'get_colleges'])->name('colleges-get');
    });
    Route::group(['middleware' => ['role_or_permission:super-admin|colleges_add']], function () {
        Route::get('/college/add', [CollegeController::class, 'create'])->name('colleges-add');
        Route::post('/college/add', [CollegeController::class, 'add_college'])->name('colleges-addCollege');
    });
    Route::group(['middleware' => ['role_or_permission:super-admin|colleges_edit|colleges_view']], function () {
        Route::get('/college/{id}', [CollegeController::class, 'get_college'])->name('college-get');
    });
    Route::group(['middleware' => ['role_or_permission:super-admin|colleges_edit']], function () {
        Route::post('/college/edit', [CollegeController::class, 'edit_college'])->name('college-edit');
    });
    Route::group(['middleware' => ['role_or_permission:super-admin|colleges_delete']], function () {
        Route::post('/college/delete', [CollegeController::class, 'delete'])->name('college-delete');
    });
    // END of functionality of Colleges

    Route::group(['middleware' => ['role_or_permission:super-admin|departments_view']], function () {
        Route::get('/departments', [DepartmentController::class, 'index'])->name('departments-list');
        Route::post('/departments', [DepartmentController::class, 'get_departments'])->name('departments-get');
    });
    Route::group(['middleware' => ['role_or_permission:super-admin|departments_add']], function () {
        Route::get('/department/add', [DepartmentController::class, 'create'])->name('departments-add');
        Route::post('/department/add', [DepartmentController::class, 'add_department'])->name('departments-addCollege');
    });
    Route::group(['middleware' => ['role_or_permission:super-admin|departments_edit|departments_view']], function () {
        Route::get('/department/{id}', [DepartmentController::class, 'get_department'])->name('department-get');
    });
    Route::group(['middleware' => ['role_or_permission:super-admin|departments_edit']], function () {
        Route::post('/department/edit', [DepartmentController::class, 'edit_department'])->name('department-edit');
    });
    Route::group(['middleware' => ['role_or_permission:super-admin|departments_delete']], function () {
        Route::post('/department/delete', [DepartmentController::class, 'delete'])->name('department-delete');
    });
    // END of functionality of Departments


    // Centers UI Routes
    Route::group(['middleware' => ['role_or_permission:super-admin|centers_view']], function () {
        Route::get('/centers', [CenterController::class, 'index'])->name('centers-list');
        Route::post('/centers', [CenterController::class, 'get_centers'])->name('centers-get');
    });
    Route::group(['middleware' => ['role_or_permission:super-admin|centers_add']], function () {
        Route::get('/center/add', [CenterController::class, 'create'])->name('centers-add');
        Route::post('/center/add', [CenterController::class, 'add_center'])->name('centers-addCollege');
    });
    Route::group(['middleware' => ['role_or_permission:super-admin|centers_edit|centers_view']], function () {
        Route::get('/center/{id}', [CenterController::class, 'get_center'])->name('center-get');
    });
    Route::group(['middleware' => ['role_or_permission:super-admin|centers_edit']], function () {
        Route::post('/center/edit', [CenterController::class, 'edit_center'])->name('center-edit');

    });
    Route::group(['middleware' => ['role_or_permission:super-admin|centers_delete']], function () {
        Route::post('/center/delete', [CenterController::class, 'delete'])->name('center-delete');

    });
    // END of functionality of centers

    // Courses UI Routes
    Route::group(['middleware' => ['role_or_permission:super-admin|courses_view']], function () {
        Route::get('/courses', [CourseController::class, 'index'])->name('courses-list');
        Route::get('/course/view/{id}', [CourseController::class, 'view_course'])->name('course-view');
        Route::post('/courses', [CourseController::class, 'get_courses'])->name('courses-get');
        Route::get('/course/export/{id}', [ExportCourseController::class, 'export_course'])->name('export-course-single');
    });

    Route::group(['middleware' => ['role_or_permission:super-admin|courses_add']], function () {
        Route::get('/course/add', [CourseController::class, 'create'])->name('courses-add');
        Route::post('/course/add', [CourseController::class, 'add_course'])->name('courses-save');
        Route::post('/course/add/import', [CourseController::class, 'import'])->name('courses-import');
        Route::get('/suggestions/course/specification', [CourseController::class, 'specification_suggestions'])->name('courses-specification-suggestions');
        Route::get('/suggestions/course/identification', [CourseController::class, 'identification_suggestions'])->name('courses-identification-suggestions');
        Route::get('/suggestions/course/teachingMode', [CourseController::class, 'teachingMode_suggestions'])->name('courses-teachingMode-suggestions');
        Route::get('/suggestions/course/contactHours', [CourseController::class, 'contactHours_suggestions'])->name('courses-contactHours-suggestions');
        Route::get('/suggestions/course/framework/{section}', [CourseController::class, 'framework_suggestions'])->name('courses-framework-suggestions');
        Route::get('/mapping-clo-with-plo', [CourseController::class, 'mapping'])->name('mapping');
    });

    Route::group(['middleware' => ['role_or_permission:super-admin|courses_edit|courses_view']], function () {
        Route::get('/course/{id}', [CourseController::class, 'get_course'])->name('course-get');
        Route::post('/export/courses', [CourseController::class, 'export'])->name('export-course');
        Route::get('/downlaod/mapping/courses', [CourseController::class, 'downloadCourses'])->name('');
    });

    Route::group(['middleware' => ['role_or_permission:super-admin|courses_edit']], function () {
        Route::get('/course/edit/{id}', [CourseController::class, 'edit'])->name('course-edit');
        Route::post('/course/update/{id}', [CourseController::class, 'update_course'])->name('course-update');
        Route::post('/course/edit/{id}', [CourseController::class, 'course'])->name('get-course');
    });

    Route::group(['middleware' => ['role_or_permission:super-admin|courses_delete']], function () {
        Route::post('/course/delete', [CourseController::class, 'delete'])->name('course-delete');
    });
    // END of functionality of courses

    // Forms UI Routes
    Route::group(['middleware' => ['role_or_permission:super-admin|forms_view']], function () {
        Route::get('/forms', [FormsController::class, 'index'])->name('forms');
        Route::post('/forms', [FormsController::class, 'get_forms'])->name('forms-get');
        Route::post('/forms/requests', [FormsController::class, 'get_forms_new_requests'])->name('forms-get-new');
        Route::get('/forms/form/{id}', [FormsController::class, 'get_form_single'])->name('form-get');
        Route::get('/forms/form/{id}/document', [FormsController::class, 'form_file'])->name('form-file-get');
        Route::get('/forms/categories', [FormsController::class, 'get_category'])->name('forms-get-category');
        Route::post('/forms/category', [FormsController::class, 'get_categorys_forms'])->name('category-get-forms');
        Route::get('/forms/review/form/{id}/{step_id}', [FormsController::class, 'review_form'])->name('review-form');
        Route::post('/forms/review/progress/{id}', [FormsController::class, 'review_form_progress'])->name('progress-review-form');
        Route::get('/form/submit-form', [FormsController::class, 'form_summation'])->name('submit-form-view');

        Route::post('/create-form-document', [PdfController::class, 'generate_document'])->name('generate-document-form');
    });

    Route::group(['middleware' => ['role_or_permission:super-admin|forms_add']], function () {
        Route::get('/form/add', [FormsController::class, 'create'])->name('forms-create');
        Route::post('/forms/add', [FormsController::class, 'add'])->name('forms-add');
        // Route::post('/forms/add/document', [FormsController::class, 'add_document'])->name('forms-add-document');
    });

    Route::group(['middleware' => ['role_or_permission:super-admin|forms_edit|forms_view']], function () {
        Route::post('/forms/form/{id}', [FormsController::class, 'get_form']);
        Route::post('/form/content/{id}', [FormsController::class, 'get_content'])->name('forms-get-conetent');
        Route::post('/forms/users', [FormsController::class, 'get_forms_users'])->name('forms-get-users');

        Route::post('/forms/repeat', [FormsController::class, 'clone_form'])->name('forms-clone');

        Route::get('/download/form/{id}', [FormsController::class, 'download_form_file'])->name('download-form-file');
        Route::post('/workflow/create', [WorkflowController::class, 'create'])->name('workflow-create');
        Route::post('/forms/form/{id}/workflows', [WorkflowController::class, 'get'])->name('workflow-get');
        Route::post('/workflows/workflow/progress', [WorkflowController::class, 'getWorkflowProgress'])->name('workflow-get-progress');
        Route::post('/workflows/workflow/order/update/{id}', [WorkflowController::class, 'update_order'])->name('workflow-update-order');
        Route::post('workflows/members', [WorkflowController::class, 'getWorkflowMembers'])->name('workflow-get-members');
        Route::post('workflows/workflow/{id}', [WorkflowController::class, 'get_workflow'])->name('workflow-get-single');
        Route::post('/forms/review/approve', [WorkflowController::class, 'form_approve'])->name('form-approve');
        Route::post('/forms/review/reject', [WorkflowController::class, 'form_reject'])->name('form-reject');
        Route::post('/forms/review/forward', [WorkflowController::class, 'form_forward'])->name('form-forward');
        Route::post('/forms/review/return', [WorkflowController::class, 'form_return'])->name('form-return');
        Route::post('/forms/form/workflow/{id}', [WorkflowController::class, 'get_workflow'])->name('form-workflow');
        // forms/review/approve /forms/form/workflow/{$workflow->id}
    });

    Route::group(['middleware' => ['role_or_permission:super-admin|forms_edit']], function () {
        Route::get('/form/edit/{id}', [FormsController::class, 'edit'])->name('form-edit');
        Route::post('/form/update', [FormsController::class, 'update'])->name('form-update');
        Route::get('/forms/add/category', [FormsController::class, 'create_category'])->name('forms-create-category');
        Route::post('/forms/add/category', [FormsController::class, 'add_category'])->name('forms-add-category');
        Route::post('/forms/edit/category/{id}', [FormsController::class, 'edit_category'])->name('forms-edit-category');
        Route::post('/forms/delete/category/{id}', [FormsController::class, 'delete_category'])->name('forms-delete-category');
    });

    Route::group(['middleware' => ['role_or_permission:super-admin|forms_delete']], function () {
        Route::post('/form/delete', [FormsController::class, 'delete'])->name('form-delete');
    });

    Route::group(['middleware' => ['role_or_permission:super-admin|forms_view']], function () {
        Route::get('/requests', [RequestController::class, 'index'])->name('requests');
        Route::post('/requests/forms', [RequestController::class, 'get_requests_forms'])->name('requests-forms');
        Route::get('/request/{id}', [RequestController::class, 'request'])->name('request');
        Route::post('/requests', [RequestController::class, 'getAll'])->name('get-all-request');
        Route::post('/requests/filters', [RequestController::class, 'filters'])->name('requests-filtred');
        Route::post('/requests/filtered', [RequestController::class, 'filtered'])->name('filtered');
        // Route::get('/requests-history', [RequestController::class, 'newRequests'])->name('history');
    });

    Route::group(['middleware' => ['role_or_permission:super-admin|courses_add']], function () {
        Route::get('/register-portal', [CourseController::class, 'register_portal'])->name('register_portal');
        Route::post('/course/register', [CourseController::class, 'register'])->name('register-course');
    });

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::post('/notification/read/{notif_id}', [NotificationController::class, 'read'])->name('read-notification');
    Route::get('/frequent-used', [FrequentUsedController::class, 'index'])->name('frequent-used');
    Route::post('/frequent-used', [FrequentUsedController::class, 'getTexts'])->name('frequent-getTexts');
    Route::post('/frequent-used/delete', [FrequentUsedController::class, 'delete'])->name('frequent-delete');
});

Route::middleware('guest')->get('/login', [LoginBasic::class, 'index'])->name('login');
Route::post('/login', [LoginBasic::class, 'auth'])->name('login.auth');
Route::post('/logout', [LoginBasic::class, 'logout'])->name('logout');
Route::get('/register', [RegisterBasic::class, 'index'])->name('register');

// Route::get('/login', [LoginBasic::class, 'index'])->name('login.basic');
Route::get('/testing', [PdfController::class, 'test'])->name('test');

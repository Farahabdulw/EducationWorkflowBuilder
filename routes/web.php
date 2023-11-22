<?php

use App\Http\Controllers\FormsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CollegeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\CenterController;
use App\Http\Controllers\GroupsController;
use App\Http\Controllers\CommitteController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\WorkflowController;
use App\Http\Controllers\NotificationController;

use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\authentications\RegisterBasic;

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
    Route::get('/', [UserController::class, 'landingPage'])->name('users');
    Route::get('/currnt_user', [UserController::class, 'get_current_user'])->name('user-current-get');

    // Users UI Routes
    Route::group(['middleware' => ['role_or_permission:super-admin|users_view']], function () {
        Route::get('/users', [UserController::class, 'index'])->name('users-list');
        Route::post('/users', [UserController::class, 'get_users'])->name('users-get');
    });
    Route::group(['middleware' => ['role_or_permission:super-admin|users_add']], function () {
        Route::get('/users/add', [UserController::class, 'addForm'])->name('user-add');
        Route::post('/user/add', [UserController::class, 'createUser'])->name('user-create');

        Route::get('/add/users/groups', [GroupsController::class, 'createUserGroup'])->name('user-create-group');
        Route::post('/add/users/groups', [GroupsController::class, 'addUsersGroup'])->name('user-add-group');
    });
    Route::group(['middleware' => ['role_or_permission:super-admin|users_edit|users_view']], function () {
        Route::get('/user/{id}', [UserController::class, 'get_user'])->name('user-get');
        Route::post('/get/group/{id}', [GroupsController::class, 'get_group'])->name('get-group-group');
        Route::post('/get/group_affiliation', [GroupsController::class, 'get_affiliations'])->name('get-group-affiliation');
        Route::post('/users/groups', [GroupsController::class, 'get_groups'])->name('get-groups');
        Route::post('/groups/users', [GroupsController::class, 'get_groups_members'])->name('groups-members');

    });
    Route::group(['middleware' => ['role_or_permission:super-admin|users_edit']], function () {
        Route::post('/user/edit', [UserController::class, 'edit_user'])->name('user-edit');

        Route::get('/edit/users/groups/{id}', [GroupsController::class, 'editUsersGroup'])->name('user-edit-group');
        Route::post('/edit/users/groups', [GroupsController::class, 'updateUsersGroup'])->name('user-edit-group');
        Route::post('/user/groups/edit/permissions', [GroupsController::class, 'edit_groups_permissions'])->name('user-groups-permissions');

    });
    Route::group(['middleware' => ['role_or_permission:super-admin|users_delete']], function () {
        Route::post('/user/delete', [UserController::class, 'delete'])->name('user-delete');
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

    // Forms UI Routes
    Route::group(['middleware' => ['role_or_permission:super-admin|forms_view']], function () {
        Route::get('/forms', [FormsController::class, 'index'])->name('forms');
        Route::post('/forms', [FormsController::class, 'get_forms'])->name('forms-get');
        Route::get('/forms/form/{id}', [FormsController::class, 'get_form_single'])->name('form-get');
        Route::get('/forms/categories', [FormsController::class, 'get_category'])->name('forms-get-category');
        Route::get('/forms/review/form/{id}/{step_id}', [FormsController::class, 'review_form'])->name('review-form');
        Route::post('/forms/review/progress/{id}', [FormsController::class, 'review_form_progress'])->name('progress-review-form');
    });
    Route::group(['middleware' => ['role_or_permission:super-admin|forms_add']], function () {
        Route::get('/form/add', [FormsController::class, 'create'])->name('forms-create');
        Route::post('/forms/add', [FormsController::class, 'add'])->name('forms-add');


    });
    Route::group(['middleware' => ['role_or_permission:super-admin|forms_edit|forms_view']], function () {
        Route::post('/forms/form/{id}', [FormsController::class, 'get_form'])->name('forms-get');
        Route::post('/form/content/{id}', [FormsController::class, 'get_content'])->name('forms-get-conetent');
        Route::post('/forms/users', [FormsController::class, 'get_forms_users'])->name('forms-get-users');

        Route::post('/workflow/create', [WorkflowController::class, 'create'])->name('workflow-create');
        Route::post('/forms/form/{id}/workflows', [WorkflowController::class, 'get'])->name('workflow-get');
        Route::post('/workflows/workflow/progress', [WorkflowController::class, 'getWorkflowProgress'])->name('workflow-get-progress');
    
    });
    Route::group(['middleware' => ['role_or_permission:super-admin|forms_edit']], function () {
        Route::get('/form/edit/{id}', [FormsController::class, 'edit'])->name('form-edit');
        Route::post('/form/update', [FormsController::class, 'update'])->name('form-update');
        Route::get('/forms/add/category', [FormsController::class, 'create_category'])->name('forms-create-category');
        Route::post('/forms/add/category', [FormsController::class, 'add_category'])->name('forms-add-category');
    });
    Route::group(['middleware' => ['role_or_permission:super-admin|forms_delete']], function () {
        Route::post('/form/delete', [FormsController::class, 'delete'])->name('form-delete');
    });

    Route::post('/notification/read/{notif_id}', [NotificationController::class, 'read'])->name('read-notification');



});

Route::get('/login', [LoginBasic::class, 'index'])->name('login');
Route::post('/login', [LoginBasic::class, 'auth'])->name('login.auth');
Route::post('/logout', [LoginBasic::class, 'logout'])->name('logout');
Route::get('/register', [RegisterBasic::class, 'index'])->name('register');

// Route::get('/login', [LoginBasic::class, 'index'])->name('login.basic');

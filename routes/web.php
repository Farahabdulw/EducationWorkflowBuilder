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

    // Users UI Routes
    Route::get('/', [UserController::class, 'index'])->name('users');
    Route::get('/users', [UserController::class, 'index'])->name('users-list');
    Route::get('/users/add', [UserController::class, 'addForm'])->name('user-add');

    // functionality of users
    Route::post('/user/add', [UserController::class, 'createUser'])->name('user-create');
    Route::post('/users', [UserController::class, 'get_users'])->name('users-get');
    Route::get('/user/{id}', [UserController::class, 'get_user'])->name('user-get');
    Route::post('/user/edit', [UserController::class, 'edit_user'])->name('user-edit');
    Route::post('/user/delete', [UserController::class, 'delete'])->name('user-delete');


    Route::get('/add/users/groups', [GroupsController::class, 'createUserGroup'])->name('user-create-group');
    Route::post('/add/users/groups', [GroupsController::class, 'addUsersGroup'])->name('user-add-group');
    Route::get('/edit/users/groups/{id}', [GroupsController::class, 'editUsersGroup'])->name('user-edit-group');
    Route::post('/edit/users/groups', [GroupsController::class, 'updateUsersGroup'])->name('user-edit-group');
    Route::post('/user/groups/edit/permissions', [GroupsController::class, 'edit_groups_permissions'])->name('user-groups-permissions');
    Route::post('/get/group/{id}', [GroupsController::class, 'get_group'])->name('get-group-group');
    Route::post('/get/group_affiliation', [GroupsController::class, 'get_affiliations'])->name('get-group-affiliation');

    Route::post('/users/groups', [GroupsController::class, 'get_groups'])->name('get-groups');
    // END of functionality of users

    // Committees UI Routes
    Route::get('/committees', [CommitteController::class, 'index'])->name('committees-list');
    Route::get('/committee/add', [CommitteController::class, 'add'])->name('committees-add');
    // END of Committees UI Routes

    // functionality of Committees
    Route::post('/committee/add', [CommitteController::class, 'addCom'])->name('committees-addCom');
    Route::post('/committees', [CommitteController::class, 'get_committees'])->name('committees-get');
    Route::get('/committee/{id}', [CommitteController::class, 'get_committee'])->name('committee-get');
    Route::post('/committee/edit', [CommitteController::class, 'edit_committee'])->name('committee-edit');
    Route::post('/committee/delete', [CommitteController::class, 'delete'])->name('committee-delete');
    // END of functionality of Committees

    // Offices UI Routes
    Route::get('/offices', [OfficeController::class, 'index'])->name('offices-list');
    Route::post('/offices', [OfficeController::class, 'get'])->name('offices-get');
    Route::get('/office/add', [OfficeController::class, 'create'])->name('offices-create');
    Route::post('/office/add', [OfficeController::class, 'add'])->name('offices-add');
    Route::get('/office/{id}', [OfficeController::class, 'office'])->name('office-get');
    Route::post('/office/edit', [OfficeController::class, 'edit'])->name('office-edit');
    Route::post('/office/delete', [OfficeController::class, 'delete'])->name('office-delete');
    // END Offices UI Routes

    // Colleges UI Routes
    Route::get('/colleges', [CollegeController::class, 'index'])->name('colleges-list');
    Route::get('/college/add', [CollegeController::class, 'create'])->name('colleges-add');
    // END Colleges UI Routes

    // functionality of Colleges
    Route::post('/college/add', [CollegeController::class, 'add_college'])->name('colleges-addCollege');
    Route::post('/colleges', [CollegeController::class, 'get_colleges'])->name('colleges-get');
    Route::get('/college/{id}', [CollegeController::class, 'get_college'])->name('college-get');
    Route::post('/college/edit', [CollegeController::class, 'edit_college'])->name('college-edit');
    Route::post('/college/delete', [CollegeController::class, 'delete'])->name('college-delete');
    // END of functionality of Colleges


    // Departments UI Routes
    Route::get('/departments', [DepartmentController::class, 'index'])->name('departments-list');
    Route::get('/department/add', [DepartmentController::class, 'create'])->name('departments-add');
    // END Departments UI Routes

    // functionality of Departments
    Route::post('/department/add', [DepartmentController::class, 'add_department'])->name('departments-addCollege');
    Route::post('/departments', [DepartmentController::class, 'get_departments'])->name('departments-get');
    Route::get('/department/{id}', [DepartmentController::class, 'get_department'])->name('department-get');
    Route::post('/department/edit', [DepartmentController::class, 'edit_department'])->name('department-edit');
    Route::post('/department/delete', [DepartmentController::class, 'delete'])->name('department-delete');
    // END of functionality of Departments


    // Centers UI Routes
    Route::get('/centers', [CenterController::class, 'index'])->name('centers-list');
    Route::get('/center/add', [CenterController::class, 'create'])->name('centers-add');
    // END Departments UI Routes

    // functionality of Departments
    Route::post('/center/add', [CenterController::class, 'add_center'])->name('centers-addCollege');
    Route::post('/centers', [CenterController::class, 'get_centers'])->name('centers-get');
    Route::get('/center/{id}', [CenterController::class, 'get_center'])->name('center-get');
    Route::post('/center/edit', [CenterController::class, 'edit_center'])->name('center-edit');
    Route::post('/center/delete', [CenterController::class, 'delete'])->name('center-delete');
    // END of functionality of Departments


    // Forms UI Routes
    Route::get('/forms', [FormsController::class, 'index'])->name('forms');
    Route::post('/forms', [FormsController::class, 'get_forms'])->name('forms-get');
    Route::get('/form/add', [FormsController::class, 'create'])->name('forms-create');
    Route::post('/form/edit', [FormsController::class, 'edit_form'])->name('form-edit');
    Route::post('/form/edit/permissions', [FormsController::class, 'edit_form_permissions'])->name('form-edit-permissions');
    Route::post('/form/delete', [FormsController::class, 'delete'])->name('form-delete');

    Route::get('/forms/categories', [FormsController::class, 'get_category'])->name('forms-get-category');
    Route::get('/forms/add/category', [FormsController::class, 'create_category'])->name('forms-create-category');
    Route::post('/forms/add/category', [FormsController::class, 'add_category'])->name('forms-add-category');

    Route::post('/forms/add', [FormsController::class, 'add'])->name('forms-add');

});
Route::get('/login', [LoginBasic::class, 'index'])->name('login');
Route::post('/login', [LoginBasic::class, 'auth'])->name('login.auth');
Route::post('/logout', [LoginBasic::class, 'logout'])->name('logout');
Route::get('/register', [RegisterBasic::class, 'index'])->name('register');

// Route::get('/login', [LoginBasic::class, 'index'])->name('login.basic');

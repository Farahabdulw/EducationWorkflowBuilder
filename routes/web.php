<?php

use App\Http\Controllers\FormsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CollegeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\CenterController;
use App\Http\Controllers\CommitteController;

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
// Route::middleware(['auth'])->group(function () {
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
    Route::post('/user/edit/permissions', [UserController::class, 'edit_user_permissions'])->name('user-edit-permissions');
    Route::post('/user/delete', [UserController::class, 'delete'])->name('user-delete');
    //END of functionality of users
    
    // Colleges UI Routes
    Route::get('/colleges', [CollegeController::class, 'index'])->name('colleges-list');
    Route::get('/colleges/add', [CollegeController::class, 'create'])->name('colleges-add');
    
    // Committees UI Routes
    Route::get('/committees', [CommitteController::class, 'index'])->name('committees-list');
    Route::get('/committee/add', [CommitteController::class, 'add'])->name('committees-add');
    Route::post('/committee/add', [CommitteController::class, 'addCom'])->name('committees-addCom');

    // Departments UI Routes
    Route::get('/departments', [DepartmentController::class, 'index'])->name('departments-list');
    Route::get('/departments/add', [DepartmentController::class, 'create'])->name('departments-add');

    // Centers UI Routes
    Route::get('/centers', [CenterController::class, 'index'])->name('centers-list');
    Route::get('/centers/add', [CenterController::class, 'create'])->name('centers-add');


    // Forms UI Routes
    Route::get('/forms', [FormsController::class, 'index'])->name('forms');
    Route::get('/forms/add', [FormsController::class, 'create'])->name('forms-add');
    Route::post('/form/edit', [FormsController::class, 'edit_form'])->name('form-edit');
    Route::post('/form/edit/permissions', [FormsController::class, 'edit_form_permissions'])->name('form-edit-permissions');
    Route::post('/form/delete', [FormsController::class, 'delete'])->name('form-delete');
    


// });

Route::get('/login', [LoginBasic::class, 'index'])->name('login');
Route::get('/register', [RegisterBasic::class, 'index'])->name('register');

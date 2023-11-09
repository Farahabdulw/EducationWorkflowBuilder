<?php

use App\Http\Controllers\FormsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CollegeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\Committe;

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
    

    Route::get('/colleges', [CollegeController::class, 'index'])->name('colleges-list');
    Route::get('/colleges/add', [CollegeController::class, 'create'])->name('colleges-add');
    
    Route::get('/committees', [Committe::class, 'index'])->name('committees-list');
    Route::get('/committees/add', [Committe::class, 'add'])->name('committees-add');
    Route::post('/committees/add', [Committe::class, 'addCom'])->name('committees-addCom');

    Route::get('/departments', [DepartmentController::class, 'index'])->name('departments-list');
    Route::get('/departments/add', [DepartmentController::class, 'create'])->name('departments-add');


    Route::get('/forms', [FormsController::class, 'index'])->name('forms');
    Route::get('/forms/add', [FormsController::class, 'create'])->name('forms-add');
    Route::post('/form/edit', [FormsController::class, 'edit_form'])->name('form-edit');
    Route::post('/form/edit/permissions', [FormsController::class, 'edit_form_permissions'])->name('form-edit-permissions');
    Route::post('/form/delete', [FormsController::class, 'delete'])->name('form-delete');
    


// });

Route::get('/login', [LoginBasic::class, 'index'])->name('login');
Route::get('/register', [RegisterBasic::class, 'index'])->name('register');

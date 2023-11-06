<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User;
use App\Http\Controllers\College;
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
    Route::get('/', [User::class, 'index'])->name('users');
    Route::get('/users', [User::class, 'index'])->name('users-list');
    Route::get('/users/add', [User::class, 'addForm'])->name('user-add');
    Route::post('/user/add', [User::class, 'createUser'])->name('user-create');
    
    Route::get('/colleges', [College::class, 'index'])->name('colleges-list');
    
    Route::get('/committees', [Committe::class, 'index'])->name('committees-list');

// });

Route::get('/login', [LoginBasic::class, 'index'])->name('login');
Route::get('/register', [RegisterBasic::class, 'index'])->name('register');

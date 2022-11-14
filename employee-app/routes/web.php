<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AuthController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');



 Route::get('login', [AuthController::class, 'index'])->name('login');
 Route::post('post-login', [AuthController::class, 'postLogin']); 
 Route::get('registration', [AuthController::class, 'registration']);
 Route::post('post-registration', [AuthController::class, 'postRegistration']); 
 Route::get('dashboard', [AuthController::class, 'dashboard'])->name('dashboard'); 
 Route::get('logout', [AuthController::class, 'logout']);


Route::get('/drake', [EmployeeController::class, 'dashboard']);
Route::get('/login', [EmployeeController::class, 'loginView']);
Route::post('/login', [EmployeeController::class, 'loginPost']);



Route::get('dashboard', [AuthController::class, 'dashboard'])->name('dashboard'); 
Route::post('/calculate-salary', [AuthController::class, 'calcSalary']);



Route::post('/mark-attendance', [APIController::class, 'attendance']);
Route::post('/mark-attendance-out', [APIController::class, 'leave']);
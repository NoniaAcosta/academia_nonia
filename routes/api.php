<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\Api\V2\StudentController as StudentV2;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    Route::apiResource('students', StudentController::class);
    Route::apiResource('courses', CourseController::class);
    Route::controller(EnrollmentController::class)->group(function () {
        Route::post('/enrollments', 'store');
        Route::get('/enrollments', 'index');
        Route::delete('/enrollments/{id}', 'destroy');
    });

    Route::prefix('v2')->group(function () {
        Route::get('/students', [StudentV2::class, 'index']);
    });
});

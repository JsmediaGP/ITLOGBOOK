<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\SupervisorController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/// Authentication route
Route::post('/Auth/login', [AuthController::class, 'login']);
Route::post('/organization/register', [SupervisorController::class, 'register']);
Route::post('/student/register', [StudentController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout'])->middleware(['auth:sanctum']);

// Routes grouped under 'auth:api' middleware
Route::middleware(['auth:api'])->group(function () {
    
    // Admin (ITCC) routes
    Route::prefix('admin')->group(function () {
        Route::post('/create-organizations', [AdminController::class, 'addOrganization']);
        Route::get('/organizations', [AdminController::class, 'viewAllOrganizations']);
        Route::get('/organizations/{id}', [AdminController::class, 'viewSingleOrganization']);
        Route::post('/create-departments', [AdminController::class, 'createDepartment']);
        Route::get('/students', [AdminController::class, 'viewAllStudents']);
        Route::get('/students/{id}/logbook', [AdminController::class, 'viewStudentLogbook']);
    });

    // Industrial-based Supervisor routes
    Route::prefix('organization')->group(function () {
        // Route::post('/register', [SupervisorController::class, 'register']);
        Route::get('/students', [SupervisorController::class, 'viewAllStudents']);
        Route::get('/students/{id}', [SupervisorController::class, 'viewSingleStudent']);
        Route::get('/students/{id}/logbook', [SupervisorController::class, 'viewStudentLogbook']);
        Route::post('/students/{id}/logbook/comment', [SupervisorController::class, 'addCommentToLogbook']);
    });

    // Student routes
    Route::prefix('student')->group(function () {
        // Route::post('/register', [StudentController::class, 'register']);
        Route::post('/fill-logbook', [StudentController::class, 'fillLogbook']);
        Route::get('/logbook', [StudentController::class, 'viewLogbook']);
    });
   
});




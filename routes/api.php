<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\OrganizationController;
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
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/organization/signup', [OrganizationController::class, 'updateOrganization']);
Route::post('/student/register', [StudentController::class, 'register']);
Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware(['auth:sanctum']);

// Routes grouped under 'auth:api' middleware
Route::middleware(['auth:sanctum'])->group(function () {
    
    // Admin (ITCC) routes
    Route::prefix('admin')->group(function () {
        Route::get('/organizations', [AdminController::class, 'viewAllOrganizations']);
        Route::get('/organizations/{id}', [AdminController::class, 'viewSingleOrganization']);
        Route::post('/create-departments', [AdminController::class, 'createDepartment']);
        Route::get('/students', [AdminController::class, 'viewAllStudents']);
        Route::get('/students/{id}/logbook', [AdminController::class, 'viewStudentLogbook']);
    });

    // Industrial-based Supervisor routes
    Route::prefix('supervisor')->group(function () {
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

    Route::prefix('organization')->group(function () {
        
        Route::post('/supervisors', [OrganizationController::class, 'viewAllSupervisors']);
        Route::post('/new-supervisor', [OrganizationController::class, 'newSupervisor']);
        Route::get('/supervisor/{id}', [OrganizationController::class, 'viewSingleSupervisor']);
        Route::get('/all-students', [OrganizationController::class, 'viewAllStudents']);
        Route::post('/assign-student', [OrganizationController::class, 'assignStudentToSupervisor']);

        
       
    });

    
    // Route::post('/supervisors', 'Api\SupervisorController@createSupervisor');
    // Route::post('/supervisors/{supervisorId}/students/{studentId}', 'Api\SupervisorController@assignStudentToSupervisor');
    // Route::get('/supervisors', 'Api\SupervisorController@viewAllSupervisorsWithStudentCount');
    // Route::get('/students', 'Api\SupervisorController@viewAllStudentsInOrganization');
   
});




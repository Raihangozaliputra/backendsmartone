<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceRecognitionLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FaceEmbeddingController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SettingsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // User Management Routes
    Route::apiResource('users', UserController::class);
    
    // Face Embedding Routes
    Route::apiResource('face-embeddings', FaceEmbeddingController::class);
    Route::post('/face/upload', [FaceEmbeddingController::class, 'upload']);
    
    // Attendance Routes
    Route::apiResource('attendances', AttendanceController::class);
    Route::post('/attendance/process', [AttendanceController::class, 'processRecognition']);
    
    // Attendance Recognition Log Routes
    Route::apiResource('attendance-recognition-logs', AttendanceRecognitionLogController::class);
    
    // Schedule Routes
    Route::apiResource('schedules', ScheduleController::class);
    
    // Classroom Routes
    Route::apiResource('classrooms', ClassroomController::class);
    
    // Holiday Routes
    Route::apiResource('holidays', HolidayController::class);
    
    // Leave Request Routes
    Route::apiResource('leave-requests', LeaveRequestController::class);
    
    // Report Routes
    Route::get('/reports/daily', [ReportController::class, 'dailyAttendance']);
    Route::get('/reports/monthly', [ReportController::class, 'monthlyRecap']);
    Route::get('/reports/late', [ReportController::class, 'lateArrivals']);
    
    // Teacher Specific Routes
    Route::prefix('teacher')->group(function () {
        Route::get('/dashboard', [TeacherController::class, 'dashboard']);
        Route::get('/students', [TeacherController::class, 'students']);
        Route::get('/student-attendance/{studentId}', [TeacherController::class, 'studentAttendance']);
        Route::post('/leave-request/{studentId}', [TeacherController::class, 'createLeaveRequest']);
    });
    
    // Admin Specific Routes
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard']);
        Route::get('/users', [AdminController::class, 'users']);
        Route::get('/classrooms', [AdminController::class, 'classrooms']);
        Route::get('/attendances', [AdminController::class, 'attendances']);
        Route::get('/statistics', [AdminController::class, 'statistics']);
    });
    
    // Settings Routes
    Route::prefix('settings')->group(function () {
        Route::get('/', [SettingsController::class, 'index']);
        Route::put('/', [SettingsController::class, 'update']);
        Route::get('/system-info', [SettingsController::class, 'systemInfo']);
        Route::get('/logs', [SettingsController::class, 'logs']);
        Route::post('/clear-cache', [SettingsController::class, 'clearCache']);
    });
});
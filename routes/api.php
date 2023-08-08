<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClassesController;
use App\Http\Controllers\ClassesWorkController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/auth/check', [AuthController::class, 'index']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/user', function (Request $request) {
        return $request->user();        
    });
    Route::post('/logout', [AuthController::class, 'logout']);
    // Classroom
    Route::post('/create-room', [ClassesController::class, 'create']);
    Route::post('/join-room/{user_id}/{class_code}', [ClassesController::class, 'joinroom']);
    Route::post('/get-room', [ClassesController::class, 'show']);
    Route::post('/room-details/{classroom_id}', [ClassesController::class, 'viewroom']);
    Route::patch('/class-unenroll/{classroom_id}', [ClassesController::class, 'unenroll']);

    Route::post('/create-task/{classroom_id}', [TaskController::class, 'createTask']);

    Route::post('/get-task/{id}/{class_code}', [TaskController::class, 'getTask']);
    Route::post('/submit-answer/{task_id}', [TaskController::class, 'submitQuiz']);
    Route::post('/get-result/{task_id}', [TaskController::class, 'getResult']);
    Route::post('/set-status', [TaskController::class, 'setStatus']);

    Route::post('/post-comment', [TaskController::class, 'postComment']);
    Route::post('/get-comment', [TaskController::class, 'getComment']);
    Route::post('/room-comments', [TaskController::class, 'roomComments']);
    Route::post('/get-completed', [TaskController::class, 'getCompleted']);

    Route::get('/get-user-profile', [UserController::class, 'getUser']);
    Route::post('/update-profile', [UserController::class, 'updateUser']);
});


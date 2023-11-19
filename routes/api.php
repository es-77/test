<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\FeebackWithCommentUser;
use App\Http\Controllers\Api\FeedBackController;
use App\Http\Controllers\Api\UsersController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'loginWithEmail']);
    Route::post('/registor', [AuthController::class, 'registor']);
    Route::post('/forgot', [AuthController::class, 'forgot']);
    Route::post('/newPassword', [AuthController::class, 'newPassword']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', [AuthController::class, 'user']);
        Route::resource('comments', CommentController::class);
        Route::resource('feedbacks', FeedBackController::class);
        Route::resource('feedbacks_commens', FeebackWithCommentUser::class);
        Route::resource('users', UsersController::class);
        Route::get('/all_users', [AuthController::class, 'getAllUser']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
});

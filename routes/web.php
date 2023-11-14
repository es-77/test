<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\FeedbackTableController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoteController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users')->middleware('admin');
    Route::delete('/user_destory/{id}', [UserController::class, 'userDestory'])->middleware('admin');
    Route::resource('feedback', FeedbackController::class);
    Route::get('feedback_table', FeedbackTableController::class)->name('feedback_table')->middleware('admin');
    Route::resource('comments', CommentController::class)->only(['index', 'destroy', 'update'])->middleware('admin');
    Route::post('/comments/{feedbackId}/comment', [CommentController::class, 'store'])->name('comment.store');
    Route::get('/feedback/{feedbackId}/comments', [CommentController::class, 'index'])->name('comment.index');
    Route::post('/feedback/{id}/vote', VoteController::class)->name('feedback.vote');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

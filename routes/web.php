<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/',[HomeController::class,'posts'])->name('posts');

Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::controller(HomeController::class)->group(function () {
    Route::get('/home', 'index')->name('home');
    Route::post('/like-post', 'likePost')->name('likePost');
    Route::post('/unlike-post', 'unlikePost')->name('unlikePost');
    Route::post('/add-comment', 'addComment')->name('addComment');
    Route::post('/get-reply-comment', 'getReply')->name('getReply');
});

Route::controller(PostController::class)->group(function () {
    Route::post('/add-post', 'store')->name('post.add');
    Route::get('/edit-post/{id}', 'edit')->name('edit');
    Route::get('/destroy/{id}', 'destroy')->name('destroy');
});
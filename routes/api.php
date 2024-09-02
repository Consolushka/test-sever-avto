<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

//Route::group(['prefix' => '/posts'], function () {
//
//    Route::get('/', [PostController::class, 'index'])
//        ->name('posts.index');
//
//    Route::get('/{post}', [PostController::class, 'show'])
//        ->name('posts.show');
//
//    Route::post('/', [PostController::class, 'store'])
//        ->name('posts.store');
//
//    Route::patch('/{post}', [PostController::class, 'update'])
//        ->name('posts.update');
//
//    Route::delete('/{post}', [PostController::class, 'destroy'])
//        ->name('posts.destroy');
//});
//
//Route::group(['prefix' => '/posts/{post}/comments'], function () {
//    Route::get('/', [CommentController::class, 'index'])
//        ->name('comments.index');
//
//    Route::get('/{commentId}', [CommentController::class, 'show'])
//        ->where('postId', '[0-9]+')
//        ->where('commentId', '[0-9]+')
//        ->name('comments.show');
//
//    Route::post('/', [CommentController::class, 'store'])
//        ->name('comments.store');;
//
//    Route::patch('/{commentId}', [CommentController::class, 'update'])
//        ->where('commentId', '[0-9]+')
//        ->name('comments.update');
//
//    Route::delete('/{commentId}', [CommentController::class, 'destroy'])
//        ->where('commentId', '[0-9]+')
//        ->name('comments.destroy');
//});


Route::apiResources([
    'posts' => PostController::class,
    'posts.comments' => CommentController::class,
]);
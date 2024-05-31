<?php

use App\Http\Controllers\Api\PeopleController;
use App\Http\Controllers\Api\SearchController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ReplyController;
use App\Http\Controllers\Api\FollowController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\FavoriteController;

Route::middleware(['auth:sanctum'])->group( function () {
    Route::post('/logged-in-user', [UserController::class,'loggedInUser']);
    Route::post('/update-user-image', [UserController::class,'updateUserImage']);
    Route::post('/updateUser', [UserController::class,'updateUser']);


    Route::post('/uploadPost', [PostController::class, 'store']);
    Route::delete('/deletePost/{id}', [PostController::class, 'destroy']);


    Route::get('/profile/{id}', [ProfileController::class,'show']);

    Route::post('/like', [LikeController::class,'store']);
    Route::post('/unLike', [LikeController::class,'destroy']);

    Route::post('/comment', [CommentController::class,'store']);
    Route::delete('/comment/{id}', [CommentController::class,'destroy']);

    Route::post('/reply', [ReplyController::class,'store']);
    Route::delete('/reply/{id}', [ReplyController::class,'destroy']);

    Route::post('/follow', [FollowController::class,'store']);
    Route::post('/unFollow', [FollowController::class,'destroy']);

    Route::post('/favorite', [FavoriteController::class,'store']);
    Route::post('/unFavorite', [FavoriteController::class,'delete']);

});

Route::get('/getAllPosts', [PostController::class,'getAllPosts']);
Route::get('/post/{id}', [PostController::class,'getPostById']);
Route::get('/getPostsUser/{id}', [PostController::class,'getAllPostsByUser']);
Route::get('/getPerson/{id}', [PeopleController::class,'getPerson']);

Route::get('/search/{content}', [SearchController::class,'search']);


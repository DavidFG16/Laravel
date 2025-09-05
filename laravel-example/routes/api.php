<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\BlogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/health', fn()=>['ok' =>true])->withoutMiddleware(['auth:api', 'role']);

Route::prefix('posts')->group(function (){
    Route::middleware(['throttle:api', 'auth:api', 'scopes:posts.read', 'role:viewer,editor,admin'])->group(function(){
        Route::get('/', [PostController::class, 'index']);
        Route::get('{posts}', [Postcontroller::class, 'show']);
    });

    Route::middleware(['throttle:api', 'auth:api', 'role:editor,admin'])->group(function(){
        Route::post('/', [PostController::class, 'store']);
        Route::put('{posts}', [Postcontroller::class, 'update']);
        Route::delete('{posts}', [Postcontroller::class, 'destroy']);
        Route::post('{posts}/restore', [Postcontroller::class, 'restore'])
        ->middleware('scopes:posts.write');
    });
});

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('signup', [AuthController::class, 'signup']);

        Route::middleware(['auth:api'])->group(function(){
        Route::get('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
});
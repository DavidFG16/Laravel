<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\BlogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/health', fn()=>['ok' =>true]);
Route::get('/health-any-auth', fn()=>['ok' =>true])->middleware(['auth:api', 'can:view-health']);
Route::get('/health-admin', fn()=>['ok' =>true])->middleware(['auth:api', 'can:view-health-admin']);

Route::prefix('posts')->group(function (){
    Route::middleware(['throttle:api', 'auth:api', 'role:viewer,editor,admin'])->group(function(){
        Route::get('/', [PostController::class, 'index']);
        Route::get('{post}', [Postcontroller::class, 'show']);
    });

    // Escritor o Administrador
    Route::middleware(['throttle:api', 'auth:api', 'role:editor,admin'])->group(function(){
        Route::post('/', [PostController::class, 'store'])->middleware(['scopes:posts.write']);
                                                                                                    // can:action, model
        Route::put('{post}', [Postcontroller::class, 'update'])->middleware(['scopes:posts.write','can:update,post']);
        Route::delete('{post}', [Postcontroller::class, 'destroy'])->middleware(['can:delete,post']);
        Route::post('{post}/restore', [Postcontroller::class, 'restore'])
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
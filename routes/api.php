<?php

use App\Modules\Chat\Controllers\ChatController;
use App\Modules\Chat\Controllers\MessageController;
use App\Modules\User\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::get('/me', [AuthController::class, 'me'])->name('me');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me'])->name('me');
    Route::post('/create-chat', [ChatController::class, 'createChat']);
    Route::post('/search', [ChatController::class, 'searchChats']);
    Route::get('/my-chats', [ChatController::class, 'myChats']);
    Route::get('/chats/{id}', [ChatController::class, 'getChat']);
    Route::apiResource('messages', MessageController::class);
});


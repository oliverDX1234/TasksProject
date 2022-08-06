<?php

use App\Http\Controllers\API\UserController;
use App\Http\Controllers\TaskController;
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

Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'register']);
Route::post('logout', [UserController::class, 'logout'])->middleware('auth:sanctum');


Route::group([ 'middleware' => ['auth:sanctum']], function(){

    Route::get('users', [UserController::class, 'allUsers']);

    Route::resource('tasks', TaskController::class);

    Route::post('move-task', [TaskController::class, 'moveTask']);
});

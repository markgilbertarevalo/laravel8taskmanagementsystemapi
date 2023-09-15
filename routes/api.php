<?php

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('register', [\App\Http\Controllers\API\UserController::class, 'register']);
Route::post('login', [\App\Http\Controllers\API\UserController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('logout', [\App\Http\Controllers\API\UserController::class, 'logout']);
    Route::apiResource('tasks', \App\Http\Controllers\API\TaskController::class);
    Route::post('subtask', [\App\Http\Controllers\API\SubTaskController::class, 'store']);
    Route::put('tasks', [\App\Http\Controllers\API\TaskController::class, 'update']);
    Route::delete('trash', [\App\Http\Controllers\API\TaskController::class, 'trash']);
    //Route::get('search', [\App\Http\Controllers\API\TaskController::class, 'search']);
});

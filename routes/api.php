<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
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
Route::post('/login',[AuthController::class,'auth']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/tasks/all', [TaskController::class,'getAll']);
    Route::get('/tasks/dateinterval', [TaskController::class,'getFilteredDate']);

    Route::post('/tasks/store', [TaskController::class,'store']);
    Route::patch('/tasks/{id}',[TaskController::class,'update']);
    Route::delete('/tasks',[TaskController::class,'delete']);

});

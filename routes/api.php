<?php

use App\Http\Controllers\TodoTaskController;
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

Route::controller(TodoTaskController::class)->group(function () {
    Route::get('/todos', 'index');
    Route::post('/todos', 'store');
    Route::put('/todos/{id}', 'update');
    Route::delete('/todos/delete-all', 'deleteAll');
    Route::delete('/todos/{id}', 'destroy');
});

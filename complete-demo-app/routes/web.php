<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::get('/', [TaskController::class, 'index']);
Route::post('/tasks', [TaskController::class, 'store']);
Route::post('/tasks/{task}/toggle', [TaskController::class, 'toggle']);
Route::post('/tasks/{task}/destroy', [TaskController::class, 'destroy']);
Route::get('/completed', [TaskController::class, 'completed']);

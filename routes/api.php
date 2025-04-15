<?php

use App\Http\Controllers\TaskController;

Route::get('/tasks/priority', [TaskController::class, 'priority'])->name('tasks.priority');
Route::apiResource('tasks', TaskController::class);


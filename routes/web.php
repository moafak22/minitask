<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

// Redirect home to tasks index
Route::get('/', function () {
    return redirect()->route('tasks.index');
});

// Test route to verify Bootstrap is working
Route::get('/test', function () {
    return view('test');
});

// Search route (must come before resource routes to avoid conflicts)
Route::get('/tasks/search', [TaskController::class, 'search'])->name('tasks.search');

// Resource route for tasks
Route::resource('tasks', TaskController::class);

<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('tasks.index');
});

Route::get('tasks', [TaskController::class, 'index'])->name('tasks.index');
Route::get('task/crear', [TaskController::class, 'create'])->name('tasks.create');
Route::post('tasks', [TaskController::class, 'store'])->name('tasks.store');
Route::patch('tasks/{task}/estado', [TaskController::class, 'changeStatus'])->name('tasks.change-status');
Route::get('tasks/{task}', [TaskController::class], 'show')->name('tasks.show');
Route::get('tasks/{task}/editar', [TaskController::class], 'edit')->name('tasks.edit');
Route::match (['put', 'patch'], 'tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
Route::delete('tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
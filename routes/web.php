<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;

Route::get('/', [FileController::class, 'index'])->name('uploads.index');
Route::get('/uploads/list', [FileController::class, 'list'])->name('uploads.list');
Route::post('/uploads', [FileController::class, 'store'])->name('uploads.store');
Route::delete('/uploads/{id}', [FileController::class, 'destroy'])->name('uploads.destroy');

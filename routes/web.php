<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\FileController;

Route::post('/upload', [FileController::class, 'putObject']);
Route::get('/download', [FileController::class, 'getObject']);
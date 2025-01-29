<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;

Route::post('/files', [FileController::class, 'putObject']);
// Route::post('/files', [FileController::class, 'postObject']);
Route::get('/files', [FileController::class, 'getObject']);


<?php

use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;

Route::post('/files', [FileController::class, 'putObject']);
Route::get('/files', [FileController::class, 'getObject']);
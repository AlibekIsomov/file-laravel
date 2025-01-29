<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;
use App\Http\Controllers\ObjectTypeController;

Route::post('/files', [FileController::class, 'putObject']);
// Route::post('/files', [FileController::class, 'postObject']);
Route::get('/files', [FileController::class, 'getObject']);



Route::apiResource('object-types', ObjectTypeController::class);

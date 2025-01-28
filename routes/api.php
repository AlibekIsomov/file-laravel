<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;

Route::put('/files', [FileController::class, 'putObject']);
Route::post('/files', [FileController::class, 'postObject']);
Route::get('/files', [FileController::class, 'getObject']);
// Route::get('/files', function () {
//     return response()->json(['message' => 'Files endpoint is working!']);
// });
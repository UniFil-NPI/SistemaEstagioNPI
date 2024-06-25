<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlunoController;
use App\Http\Controllers\AuthController;

Route::get('/auth/google', [AuthController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

Route::get('/', function () {
    return view('landing');
});

Route::get('/bimestres', function () {
    return view('bimestres');
});

Route::middleware('auth')->get('/listaralunos/{bimestre}', [AlunoController::class, 'getAlunosPorBimestre']);

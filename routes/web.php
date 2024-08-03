<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlunoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuarioController;

Route::get('/auth/google', [AuthController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

Route::get('/', function () {
    return view('landing');
});

Route::middleware('auth')->get('/bimestres', function () {
    return view('bimestres');
});

Route::middleware('auth')->get('/admin', function () {
    return view('admin');
});

Route::middleware('auth')->get('/admin/alunosadmin', [AlunoController::class, 'pegarAlunosAtivos']);
Route::middleware('auth')->get('/admin/alunosadmin/alterarbimestre/{aluno_email}{bimestre}', [AlunoController::class, 'alterarBimestre']);
Route::middleware('auth')->get('/admin/alunosadmin/alterarprofessor/{aluno_enail}{professor_email}', [AlunoController::class, 'alterarProfessor']);
Route::middleware('auth')->get('/admin/alunosadmin/desativar/{aluno_email}', [AlunoController::class, 'desativar']);

Route::middleware('auth')->get('/admin/alunosdesativados', [AlunoController::class, 'pegarAlunosDesativos']);
Route::middleware('auth')->get('/admin/alunosdesativados/{email}', [AlunoController::class, 'reativar']);

Route::middleware('auth')->get('/admin/professoresadmin', [UsuarioController::class, 'pegarUsuariosAtivos']);
Route::middleware('auth')->get('/admin/professoresadmin/desativar/{email_professor}', [UsuarioController::class, 'pegarUsuariosAtivos']);
Route::middleware('auth')->get('/admin/professoresadmin/controlaralunos/{email_professor}', [UsuarioController::class, 'pegarUsuariosAtivos']);

Route::middleware('auth')->get('/admin/professoresdesativados', [UsuarioController::class, 'pegarUsuariosDesativos']);



Route::middleware('auth')->get('/listaralunos/{bimestre}', [AlunoController::class, 'pegarAlunosPorBimestre']);

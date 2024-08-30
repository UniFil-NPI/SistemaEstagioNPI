<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlunoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ClassroomController;

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

Route::post('/admin/alunosadmin/salvar-dados', [AlunoController::class, 'salvarDados']);


//patch
Route::middleware('auth')->get('/admin/alunosadmin', [AlunoController::class, 'pegarInformacoesAdmin']);
Route::middleware('auth')->post('/admin/alunosadmin/alterarbimestre/{bimestre}/aluno/{email_aluno}', [AlunoController::class, 'alterarEtapa']);
Route::middleware('auth')->post('/admin/alunosadmin/alterarprofessor/{professor_email}/aluno/{email_aluno}', [AlunoController::class, 'alterarProfessor']);
Route::middleware('auth')->post('/admin/alunosadmin/desativar/{aluno_email}', [AlunoController::class, 'desativar']);
Route::middleware('auth')->post('/admin/alunosadmin/cadastrarcurso/{id}/owner/{ownerId}/nome/{nome}', [ClassroomController::class, 'adicionarCursoBanco']);
Route::middleware('auth')->post('/admin/alunosadmin/adicionaralunos/classroom/{idClassroom}', [AlunoController::class, 'adicionarAlunos']);

Route::middleware('auth')->get('/admin/alunosdesativados', [AlunoController::class, 'pegarAlunosDesativos']);
Route::middleware('auth')->get('/admin/alunosdesativados/{email}', [AlunoController::class, 'reativar']);

Route::middleware('auth')->get('/admin/professoresadmin', [UsuarioController::class, 'pegarUsuariosAtivosView']);
Route::middleware('auth')->get('/admin/professoresadmin/desativar/{email_professor}', [UsuarioController::class, 'desativar']);
Route::middleware('auth')->get('/admin/professoresadmin/reativar/{email_professor}', [UsuarioController::class, 'reativar']);
Route::middleware('auth')->get('/admin/professoresadmin/controlaralunos/{email_professor}', [UsuarioController::class, 'pegarUsuariosAtivosView']);
Route::middleware('auth')->get('/admin/professoresadmin/darprivilegios/{email_professor}', [UsuarioController::class, 'darPrivilegiosAdmin']);
Route::middleware('auth')->get('/admin/professoresadmin/tirarprivilegios/{email_professor}', [UsuarioController::class, 'tirarPrivilegiosAdmin']);

Route::middleware('auth')->get('/admin/professoresdesativados', [UsuarioController::class, 'pegarUsuariosDesativos']);



Route::middleware('auth')->get('/listaralunos/{bimestre}', [AlunoController::class, 'pegarAlunosPorEtapa']);



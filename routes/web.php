<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlunoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\RPODSController;
use App\Http\Controllers\OrientacoesController;
use App\Http\Controllers\AtividadesController;


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


Route::middleware('auth')->post(
    '/admin/alunosadmin/passaretapamanual/finalizar',
    [AlunoController::class, 'finalizarEtapa']);


Route::middleware('auth')->post(
    '/admin/alunosadmin/passaretapamanual/{etapa}/novoclassroom/{id_classroom}',
    [AlunoController::class, 'avaliarAprovacao']);



Route::middleware('auth')->get(
    '/atividades/{email_aluno}/etapa/{etapa_aluno}',
    [AtividadesController::class, 'pegarClassroomsAtividadesAluno']);

Route::middleware('auth')->post(
    '/atividades/{id_classroom}/email/{email_aluno}/salvarnotas', 
    [AtividadesController::class, 'salvarNotas']);

Route::middleware('auth')->get(
    '/atividades/calcularmedia/{email_aluno}/datacomeco/{data_comeco}/datafim/{data_fim}',
    [ClassroomController::class, 'pegarClassroomsAluno']);



Route::middleware('auth')->get(
    '/orientacoes/{email_aluno}/etapa/{etapa_aluno}',
    [OrientacoesController::class, 'pegarOrientacoes']);

Route::middleware('auth')->post(
    '/orientacoes/registrar/{email_aluno}/etapa/{etapa_aluno}',
    [OrientacoesController::class, 'registrarOrientacao']);

Route::middleware('auth')->post(
    '/orientacoes/deletar/{id_orientacao}/email/{email_aluno}/etapa/{etapa_aluno}',
    [OrientacoesController::class, 'excluirOrientacao']);

Route::middleware('auth')->post(
    '/orientacoes/cargahoraria/{email_aluno}',
    [OrientacoesController::class, 'calcularCargaHoraria']);


Route::middleware('auth')->get('/rpods/{email_aluno}/etapa/{etapa_aluno}', [RPODSController::class, 'pegarRPODS']);
Route::middleware('auth')->post('/rpods/registrar/{data}/email/{email_aluno}/etapa/{etapa_aluno}', [RPODSController::class, 'registrarRPOD']);
Route::middleware('auth')->post('/rpods/excluir/{email_aluno}/id/{id_rpod}/etapa/{etapa_aluno}', [RPODSController::class, 'excluirRPOD']);
Route::middleware('auth')->post('/rpods/quantidade/{email_aluno}', [RPODSController::class, 'quantidadeRPODS']);




Route::post('/admin/alunosadmin/salvar-dados', [AlunoController::class, 'salvarDados']);


//patch
Route::middleware('auth')->get('/admin/alunosadmin', [AlunoController::class, 'pegarInformacoesAdmin']);
Route::middleware('auth')->post('/admin/alunosadmin/alterarbimestre/{bimestre}/aluno/{email_aluno}', [AlunoController::class, 'alterarEtapa']);
Route::middleware('auth')->post('/admin/alunosadmin/alterarprofessor/{professor_email}/aluno/{email_aluno}', [AlunoController::class, 'alterarProfessor']);
Route::middleware('auth')->post('/admin/alunosadmin/desativar/{aluno_email}', [AlunoController::class, 'desativar']);
Route::middleware('auth')->post('/admin/alunosadmin/alterarstatus/{aluno_email}', [AlunoController::class, 'alterarStatus']);
Route::middleware('auth')->post('/admin/alunosadmin/cadastrarcurso/{id}/owner/{ownerId}/nome/{nome}', [ClassroomController::class, 'adicionarCursoBanco']);
Route::middleware('auth')->post('/admin/alunosadmin/adicionaralunos/classroom/{idClassroom}', [AlunoController::class, 'adicionarAlunos']);
Route::middleware('auth')->post('/admin/alunosadmin/concluiretapa/{etapa}/classroomnovo/{id_classroom}', [AlunoController::class, 'adicionarAlunos']);


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



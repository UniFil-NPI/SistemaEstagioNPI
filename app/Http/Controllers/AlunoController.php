<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Classroom;
use App\Models\Users;
use Illuminate\Http\Request;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\OrientacoesController;
use App\Http\Controllers\AtividadesController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use League\Csv\Reader;
use League\Csv\Statement;

class AlunoController extends Controller
{
    public function pegarAlunosPorEtapa($etapa)
    {
        $emailProfessor = Auth::user()->email;
        $alunos = Aluno::where('email_orientador', $emailProfessor)
                       ->where('etapa', $etapa)
                       ->get();

        return view('listaralunos', compact('alunos'));
    }

    public function pegarAlunosDesativos()
    {
        $alunos = Aluno::where('ativo',FALSE)->get();

        return view('alunosdesativados',compact('alunos'));
    }

    public function pegarInformacoesAdmin()
    {
        $alunos = Aluno::where('ativo', TRUE)->get();

        $usuarioController = new UsuarioController();
        $users = $usuarioController->pegarUsuariosAtivos();

        $classroomController = new ClassroomController;
        $classroomData = $classroomController->listarCursosUserAutenticado();
        $classroomBanco = $classroomController->pegarClassrooms();

        $classroomUser = [];
        foreach ($classroomData['courses'] as $course) {
            $classroomUser[] = [
                'id' => $course['id'],
                'ownerId' => $course['ownerId'],
                'nome' => $course['name'],
            ];
        }
        return view('alunosadmin', compact('alunos', 'users', 'classroomUser', 'classroomBanco'));
    }



    public function adicionarAlunos(Request $request, $idClassroom)
    {
        $matriculas = [];
        $nomes = [];
        $emails = [];
        $emailsProfessores = [];
    
        $validator = Validator::make($request->all(), [
            'arquivoCSV' => 'required|file|mimes:csv,txt',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        if ($request->hasFile('arquivoCSV')) {
            $file = $request->file('arquivoCSV');
            $csv = Reader::createFromPath($file->getPathname(), 'r');
            $csv->setDelimiter(',');
            $records = Statement::create()->process($csv);

    
            foreach ($records as $record) {
                $matriculas[] = $record[0] ?? null;
                $nomes[] = $record[1] ?? null;
                $emails[] = null;
                $emailsProfessores[] = null;
            }
    
            $classroomController = new ClassroomController;
            $alunosClassroom = $classroomController->pegarAlunosCurso($idClassroom);

            foreach ($nomes as $index => $nome) {
                foreach ($alunosClassroom as $alunoClassroom) {
                    if (strcasecmp($nome, $alunoClassroom['name']) === 0) {
                        $emails[$index] = $alunoClassroom['email'];
                        break;
                    }
                }
            }
    
            $alunosParaVerificacao = [];
            $i = 0;
            foreach ($matriculas as $index => $matricula) {
                $alunosParaVerificacao[] = [
                    'matricula' => $matricula,
                    'nome' => $nomes[$index],
                    'email' => $emails[$i],
                    'emailProfessor' => $emailsProfessores[$index],
                ];
                $i = $i + 1;
            }
    
            $usuarioController = new UsuarioController();
            $users = $usuarioController->pegarUsuariosAtivos();
            return view('adicionaralunosmanual', [
                'alunosIncompletos' => $alunosParaVerificacao,
                'idClassroom' => $idClassroom,
                'users' => $users
            ]);
        }
    
        return redirect()->back()->with('error', 'Falha ao processar o arquivo.');
    }
    

    public function salvarDados(Request $request)
    {
        $matriculas = $request->input('matriculas');
        $nomes = $request->input('nomes');
        $emails = $request->input('emails');
        $emailsProfessores = $request->input('emailsProfessores');
        $idClassroom = $request->input('id_classroom');

        foreach ($matriculas as $index => $matricula) {
            $aluno = Aluno::updateOrCreate(
                ['email_aluno' => $emails[$index]],
                [
                    'nome' => $nomes[$index],
                    'matricula' => $matricula,
                    'email_orientador' => $emailsProfessores[$index],
                    'etapa' => 1,
                    'ativo' => true,
                    'pendente' => false,
                ]
            );

            $aluno->classrooms()->updateExistingPivot($idClassroom, ['atual' => false]);
            
            $aluno->classrooms()->syncWithoutDetaching([
                $idClassroom => ['atual' => true]
            ]);
        }

        return redirect('/admin/alunosadmin')->with('success', 'Dados dos alunos salvos com sucesso!');
    }


    public function alterarProfessor($emailProfessor, $emailAluno)
    {
        $professor = Users::where('email', $emailProfessor)->first();

        $aluno = Aluno::where('email_aluno', $emailAluno)->first();

        if ($aluno) {
            $aluno->email_orientador = $emailProfessor;
            $aluno->save();
        } else {
            return response()->json(['error' => 'Aluno não encontrado.'], 404);
        }

        return redirect()->back()->with('success', 'Professor do aluno alterado com sucesso!');
    }

    public function alterarEtapa($etapa,$emailAluno)
    {
        $aluno = Aluno::where('email_aluno',$emailAluno)->first();

        if($aluno){
            $aluno->etapa = $etapa;
            $aluno->save();
        }

        return redirect()->back()->with('success', 'Etapa do aluno alterada com sucesso!');
    }


    public function desativar($emailAluno)
    {
        $aluno = Aluno::where('email_aluno',$emailAluno)->first();
        if($aluno){
            $aluno->ativo = FALSE;
            $aluno->save();
        }
        return redirect()->back()->with('success', 'Aluno desativado com sucesso!');
    }


    public function alterarStatus($emailAluno)
    {
        $aluno = Aluno::where('email_aluno',$emailAluno)->first();
        if($aluno){
            if($aluno->pendente){
                $aluno->pendente = FALSE;
            }
            else{
                $aluno->pendente = TRUE;
            }
            $aluno->save();
        }
        return redirect()->back()->with('success', 'Status do aluno alterado com sucesso!');
    }


    public function reativar($emailAluno)
    {
        $aluno = Aluno::where('email_aluno',$emailAluno)->first();

        if($aluno){
            $aluno->ativo = TRUE;
            $aluno->save();
        }

        return redirect()->back()->with('success', 'Aluno reativado com sucesso!');
    }


    public function avaliarAprovacao($etapa, $novoClassroomID = null)
    {
        $alunos = Aluno::where('aluno.etapa', $etapa)->get();


        $atividadesController = new AtividadesController();
        $orientacoesController = new OrientacoesController();
        $classroomController = new ClassroomController();


        $alunosAvaliados = [];

        foreach ($alunos as $aluno) {
            $classroomAtual = $aluno->classrooms()->wherePivot('atual', true)->first();

            if ($classroomAtual) {
                $id_classroom = $classroomAtual->id_classroom;
            }

            $mediaFinal = $atividadesController->calcularNotaFinal($id_classroom, $aluno->email_aluno);
            $cargaHoraria = $orientacoesController->calcularCargaHoraria($aluno->email_aluno);

            $aprovado = $mediaFinal >= 70 && $cargaHoraria >= 4;

            $statusNovoClassroom = $novoClassroomID 
                ? $classroomController->verificarAlunosClassroom($aluno->email_aluno, $novoClassroomID)
                : 'N/A';

            $alunosAvaliados[] = [
                'nome' => $aluno->nome,
                'email' => $aluno->email_aluno,
                'media' => $mediaFinal,
                'cargaHoraria' => $cargaHoraria,
                'status' => $aprovado ? 'Aprovado' : 'Pendente',
                'novoClassroom' => $statusNovoClassroom,
            ];

        }

        return view('passaretapamanual', compact('alunosAvaliados', 'etapa', 'novoClassroomID','classroomAtual'));
    }


    public function finalizarEtapa(Request $request)
{
    $classroomID = $request->input('classroom_id');
    $classroomAtual = $request->input('classroom_atual');
    $etapa = $request->input('etapa');
    $aprovados = $request->input('aprovados', []);
    dd($aprovados);

    $alunos = Aluno::where('etapa', $etapa)->get();

    if ($alunos->isEmpty()) {
        return redirect('/admin/alunosadmin')->with('error', 'Nenhum aluno encontrado para esta turma e etapa.');
    }

    foreach ($alunos as $aluno) {
        $aprovado = in_array($aluno->email_aluno, $aprovados);

        if ($aprovado) {
            if ($aluno->etapa < 4) {
                $aluno->etapa += 1;
            } else {
                $aluno->ativo = false;
            }
            $aluno->save();
        } else {
            $aluno->pendente = true;
            $aluno->save();
            continue;
        }

        if ($classroomID && $classroomID != $classroomAtual) {
            $this->mudarClassroom($aluno, $classroomID);
        }
    }

    return redirect('/admin/alunosadmin')->with('success', 'Avaliação finalizada com sucesso.');
}





    public function mudarClassroom(Aluno $aluno, $novoClassroomId = null)
    {
        $classroomAtual = $aluno->classrooms()->wherePivot('atual', true)->first();

        if ($classroomAtual) {
            $aluno->classrooms()->updateExistingPivot($classroomAtual->id_classroom, ['atual' => false]);
        }

        if ($novoClassroomId) {
            $aluno->classrooms()->attach($novoClassroomId, ['atual' => true]);
        } else {
            $aluno->classrooms()->updateExistingPivot($classroomAtual->id_classroom, ['atual' => true]);
        }

        $aluno->etapa += 1;
        $aluno->pendente = false;
        $aluno->save();
    }



}
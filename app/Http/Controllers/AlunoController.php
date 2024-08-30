<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use Illuminate\Http\Request;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ClassroomController;
use League\Csv\Reader;
use League\Csv\Statement;
use Illuminate\Support\Facades\Validator;


use App\Models\Users;
use Illuminate\Support\Facades\Auth;

class AlunoController extends Controller
{
    public function pegarAlunosPorEtapa($etapa)
    {
        $emailProfessor = Auth::user()->email;
        $alunos = Aluno::where('email_orientador', $emailProfessor)
                       ->where('etapa', $etapa)
                       ->get();

        $alunos->transform(function ($aluno) {
            if (is_null($aluno->matricula)) {
                $aluno->matricula = "Matrícula não cadastrada";
            }
            return $aluno;
        });

        return view('listaralunos', compact('alunos'));
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

    public function filtrarAlunosAtivos($filtros)
    {
        $alunos = Aluno::where('ativo',TRUE)->get();

        return view('alunosadmin',compact('alunos'));
    }

    public function pegarAlunosDesativos()
    {
        $alunos = Aluno::where('ativo',FALSE)->get();

        return view('alunosdesativados',compact('alunos'));
    }

    public function desativar($emailAluno)
    {
        $aluno = Aluno::where('email_aluno',$emailAluno)->first();

        if($aluno){
            $aluno->ativo = FALSE;
            $aluno->save();
        }

        return $this->pegarInformacoesAdmin();
    }

    public function reativar($emailAluno)
    {
        $aluno = Aluno::where('email_aluno',$emailAluno)->first();

        if($aluno){
            $aluno->ativo = TRUE;
            $aluno->save();
        }

        return $this->pegarAlunosDesativos();
    }

    public function alterarProfessor($emailProfessor, $emailAluno)
    {
        $professor = Users::where('email', $emailProfessor)->first();

        if (!$professor) {
            // Retorne um erro ou uma mensagem informando que o professor não existe
            echo "<script>console.log('Email professor recebido: " . json_encode($emailProfessor) . "')</script>";
            echo "<script>console.log('Email aluno recebido: " . json_encode($emailAluno) . "')</script>";

        }

        $aluno = Aluno::where('email_aluno', $emailAluno)->first();

        if ($aluno) {
            $aluno->email_orientador = $emailProfessor;
            $aluno->save();
        } else {
            // Retorne uma mensagem caso o aluno não seja encontrado
            return response()->json(['error' => 'Aluno não encontrado.'], 404);
        }

        return $this->pegarInformacoesAdmin();
    }

    public function alterarEtapa($etapa,$emailAluno)
    {
        $aluno = Aluno::where('email_aluno',$emailAluno)->first();

        if($aluno){
            $aluno->etapa = $etapa;
            $aluno->save();
        }

        return $this->pegarInformacoesAdmin();
    }

 ////////////////////////////////////////////////////////////////////////////////////////////////////


    public function adicionarAlunos(Request $request, $idClassroom)
    {
        $matriculas = [];
        $nomes = [];
        $emails = [];
        $emailsProfessores = [];
    
        // Validação do arquivo CSV
        $validator = Validator::make($request->all(), [
            'arquivoCSV' => 'required|file|mimes:csv,txt',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        if ($request->hasFile('arquivoCSV')) {
            $file = $request->file('arquivoCSV');
            
            #$csv = Reader::createFromPath($file->getPathname(), 'r');
            #$csv->setHeaderOffset(0);
            #$records = Statement::create()->process($csv);
            $csv = Reader::createFromPath($file->getPathname(), 'r');
            $csv->setDelimiter(',');  // Adicione esta linha para definir a vírgula como delimitador
            #$csv->setHeaderOffset(0);
            $records = Statement::create()->process($csv);

    
            foreach ($records as $record) {
                $matriculas[] = $record[0] ?? null;
                $nomes[] = $record[1] ?? null;
                $emails[] = null;  // Preencheremos este campo após buscar os dados no Classroom
                $emailsProfessores[] = null; // Deixe nulo para adicionar posteriormente
            }
    
            // Pegar os alunos do Google Classroom
            $classroomController = new ClassroomController;
            $alunosClassroom = $classroomController->pegarAlunosCurso($idClassroom);

            // Associar os emails dos alunos do Google Classroom com base no nome
            foreach ($nomes as $index => $nome) {
                foreach ($alunosClassroom as $alunoClassroom) {
                    if (strcasecmp($nome, $alunoClassroom['name']) === 0) {
                        $emails[$index] = $alunoClassroom['email'];
                        break;
                    }
                }
            }

            //dd($alunosClassroom);
    
            // Preparar todos os alunos para visualização
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
    
            // Redirecionar todos os alunos para a página de verificação manual
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
            $aluno = Aluno::where('matricula', $matricula)->first();
    
            if (!$aluno) {
                $aluno = new Aluno();
                $aluno->matricula = $matricula;
            }
    
            $aluno->nome = $nomes[$index];
            $aluno->email_aluno = $emails[$index];
            $aluno->email_orientador = $emailsProfessores[$index];
            $aluno->id_classroom = $idClassroom;
            $aluno->etapa = 1;
            $aluno->ativo = true;
            $aluno->pendente = false;
            $aluno->save();
        }
    
        return redirect('/admin/alunosadmin')->with('success', 'Dados dos alunos salvos com sucesso!');
    }

}

// f29400 laranja
// f707173 cinza

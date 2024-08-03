<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use Illuminate\Http\Request;
use App\Models\Users;
use Illuminate\Support\Facades\Auth;

class AlunoController extends Controller
{
    public function pegarAlunosPorBimestre($bimestre)
    {
        $emailProfessor = Auth::user()->email;
        $alunos = Aluno::where('email_orientador', $emailProfessor)
                       ->where('bimestre', $bimestre)
                       ->get();

        $alunos->transform(function ($aluno) {
            if (is_null($aluno->matricula)) {
                $aluno->matricula = "Matrícula não cadastrada";
            }
            return $aluno;
        });

        return view('listaralunos', compact('alunos'));
    }

    public function pegarAlunosAtivos()
    {
        $alunos = Aluno::where('ativo',TRUE)->get();

        return view('alunosadmin',compact('alunos'));
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

        return $this->pegarAlunosAtivos();
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

    public function alterarProfessor($emailAluno,$emailProfessor)
    {
        $aluno = Aluno::where('email_aluno',$emailAluno)->first();

        if($aluno){
            $aluno->email_orientador = $emailProfessor;
            $aluno->save();
        }

        return $this->pegarAlunosAtivos();
    }

    public function adicionarAlunos()
    {
        //pegar alunos do csv
        //chamar métodos do ClassroomController
    }

    public function alterarBimestre($emailAluno,$bimestre)
    {
        $aluno = Aluno::where('email',$emailAluno)->first();

        if($aluno){
            $aluno->bimestre = $bimestre;
            $aluno->save();
        }

        //mudar curso do classroom

        return $this->pegarAlunosAtivos();
    }

}

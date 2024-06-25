<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use Illuminate\Http\Request;
use App\Models\Users;
use Illuminate\Support\Facades\Auth;

class AlunoController extends Controller
{
    public function getAlunosPorBimestre($bimestre)
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
}

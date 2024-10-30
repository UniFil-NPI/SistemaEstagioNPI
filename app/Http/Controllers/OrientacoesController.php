<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orientacao;

class OrientacoesController extends Controller
{

    public function pegarOrientacoes($email_aluno,$etapa_aluno)
    {
        $orientacoes = Orientacao::where('email_aluno', $email_aluno)->get();
        return view('orientacoes', compact('orientacoes','email_aluno','etapa_aluno'));
    }

    public function registrarOrientacao(Request $request, $email_aluno, $etapa_aluno)
    {
        $request->validate([
            'comparecimento' => 'required|boolean',
            'descricao' => 'required|string|max:255',
            'grauSatisfacao' => 'required|integer|min:1|max:5',
        ]);

        $orientacao = new Orientacao();
        $orientacao->email_aluno = $email_aluno;
        $orientacao->comparecimento = $request->comparecimento;
        $orientacao->descricao = $request->descricao;
        $orientacao->grau_satisfacao = $request->grauSatisfacao;
        $orientacao->data_orientacao = now(); // Data atual
        $orientacao->save();

        return $this->pegarOrientacoes($email_aluno, $etapa_aluno);
    }

    public function excluirOrientacao($id_orientacao, $email_aluno ,$etapa_aluno)
    {
        $orientacao = Orientacao::find($id_orientacao);
        if ($orientacao) {
            $orientacao->delete();
        }

        return $this->pegarOrientacoes($email_aluno, $etapa_aluno);
    }

    public function calcularCargaHoraria($email_aluno)
    {
        $cargaHoraria = Orientacao::where('email_aluno', $email_aluno)
            ->where('comparecimento', true)
            ->count();

        return $cargaHoraria;
    }
}

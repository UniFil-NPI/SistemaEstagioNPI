<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use App\Models\Atividade;
use App\Models\Aluno;

class AtividadesController extends Controller
{
    public function pegarClassroomsAtividadesAluno($email_aluno,$etapa_aluno)
    {
        $token = Cookie::get('google_login_token');
        $refreshToken = Cookie::get('google_login_refresh_token');

        Artisan::call('atividades:sincronizar', [
            'token' => $token,
            'refreshToken' => $refreshToken,
        ]);

        Artisan::call('classrooms:verificar', [
            'token' => $token,
            'refreshToken' => $refreshToken,
        ]);
        
        $aluno = Aluno::with('classrooms.atividades')
                    ->where('email_aluno', $email_aluno)
                    ->firstOrFail();

        $classrooms = $aluno->classrooms;

        return view('atividades', compact('classrooms','etapa_aluno'));
    }


    public function salvarNotas(Request $request, $classroomId) {
        $notas = $request->input('notas', []);
    
        foreach ($notas as $atividadeId => $nota) {
            Atividade::where('id_atividade', $atividadeId)
                     ->update(['nota' => $nota]);
        }
    
        return redirect()->back()->with('success', 'Notas salvas com sucesso!');
    }
    


    public function calcularNotaFinal($id_classroom, $data_inicio = null, $data_fim = null)
    {
        $query = Atividade::where('id_classroom', $id_classroom);

        if ($data_inicio && $data_fim) {
            $query->where('data_criacao', '>=', $data_inicio);
            $query->where('data_criacao', '<=', $data_fim);
        }

        $atividades = $query->get();

        if ($atividades->isEmpty()) {
            return "Nenhuma atividade encontrada para este classroom.";
        }

        $somaNotas = $atividades->sum('nota');
        $quantidadeNotas = $atividades->count();
        $mediaFinal = $somaNotas / $quantidadeNotas;

        return $mediaFinal;
    }


}

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
        
        $aluno = Aluno::with(['classrooms.atividades' => function ($query) use ($email_aluno) {
            $query->where('email_aluno', $email_aluno);
        }])
        ->where('email_aluno', $email_aluno)
        ->firstOrFail();

        $classrooms = $aluno->classrooms;

        return view('atividades', compact('classrooms','etapa_aluno','email_aluno'));
    }


    public function salvarNotas(Request $request, $classroomId, $email_aluno) {
        $notas = $request->input('notas', []);
    
        foreach ($notas as $atividadeId => $nota) {
            $atividade = Atividade::where('id_atividade', $atividadeId)
                        ->where('email_aluno', $email_aluno)
                        ->first();
    
            if ($atividade) {
                $atividade->nota = (int)$nota;
    
                if (!$atividade->save()) {
                    Log::error("Falha ao salvar a nota para a atividade $atividadeId");
                }
            } else {
                Log::error("Atividade com ID $atividadeId não encontrada para o aluno $email_aluno");
            }
        }
    
        return redirect()->back()->with('success', 'Notas salvas com sucesso!');
    }
    


    public function calcularNotaFinal($id_classroom, $email_aluno)
    {
        $aluno = Aluno::where('email_aluno', $email_aluno)->first();

        if (!$aluno) {
            return "Aluno não encontrado.";
        }

        $atividades = Atividade::where('id_classroom', $id_classroom)->where('email_aluno',$email_aluno)->get();

        if ($atividades->isEmpty()) {
            return "Nenhuma atividade encontrada para este classroom e aluno.";
        }

        $somaNotas = $atividades->sum('nota');
        $quantidadeNotas = $atividades->count();
        $mediaFinal = $somaNotas / $quantidadeNotas;

        return $mediaFinal;
    }


}

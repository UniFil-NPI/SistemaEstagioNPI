<?php

namespace App\Http\Controllers;

use App\Models\RPOD;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RPODSController extends Controller
{

    public function pegarRPODS($email_aluno,$etapa_aluno)
    {
        $rpods = RPOD::where('email_aluno', $email_aluno)->get();
        return view('rpods', compact('rpods', 'email_aluno','etapa_aluno'));
    }


    public function registrarRPOD(Request $request, $data, $email_aluno, $etapa_aluno)
    {
        $validator = Validator::make($request->all(), [
            'rpod' => 'required|file|mimes:pdf',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $existingRpod = RPOD::where('data_entrega', $data)
                            ->where('email_aluno', $email_aluno)
                            ->first();

        if ($existingRpod) {
            return redirect("/rpods/$email_aluno/etapa/$etapa_aluno")->with('error', 'Já existe um RPOD cadastrado para essa data.')->withInput();
        }

        if ($request->hasFile('rpod')) {
            $file_base64 = base64_encode(file_get_contents($request->file('rpod')));

            $rpod = new RPOD();
            $rpod->data_entrega = $data;
            $rpod->base_64 = $file_base64;
            $rpod->email_aluno = $email_aluno;
            $rpod->save();
        }

        return $this->pegarRPODS($email_aluno, $etapa_aluno);
    }


    public function excluirRPOD($email_aluno, $id_rpod, $etapa_aluno)
    {
        $rpod = RPOD::where('email_aluno', $email_aluno)->where('id_rpod', $id_rpod)->first();

        if ($rpod) {
            $rpod->delete();
        }

        return $this->pegarRPODS($email_aluno,$etapa_aluno);
    }

    public function quantidadeRPODS($email_aluno)
    {
        $total = RPOD::where('email_aluno', $email_aluno)->count();
        return response()->json(['total' => $total]);
    }
}

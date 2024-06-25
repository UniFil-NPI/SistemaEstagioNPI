<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\Users;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            Log::error('Erro ao autenticar com o Google: ' . $e->getMessage());
            return redirect('/')->with('error', 'Erro ao autenticar com o Google.');
        }

        // Verifique se o usuário já existe no banco de dados
        $existingUser = Users::where('email', $user->email)->first();

        if ($existingUser) {
            auth()->login($existingUser);
        } else {
            try {
                // Usuário não existe, crie um novo registro
                $newUser = new Users();
                $newUser->email = $user->email;
                $newUser->nome = $user->name; // Ajuste conforme a estrutura da sua tabela
                $newUser->isadmin = false; // Definindo isadmin como false
                $newUser->ativo = true; // Definindo ativo como true
                $newUser->save();

                auth()->login($newUser);
            } catch (\Exception $e) {
                Log::error('Erro ao criar novo usuário: ' . $e->getMessage());
                return redirect('/')->with('error', 'Erro ao criar novo usuário.');
            }
        }

        return redirect('/bimestres');
    }
}


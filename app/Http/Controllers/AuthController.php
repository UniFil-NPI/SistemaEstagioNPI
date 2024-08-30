<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\Users;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->scopes(['profile', 'email', 'openid','https://www.googleapis.com/auth/classroom.courses.readonly'
        ,'https://www.googleapis.com/auth/classroom.rosters.readonly','https://www.googleapis.com/auth/classroom.courses.readonly',
        'https://www.googleapis.com/auth/classroom.profile.emails','https://www.googleapis.com/auth/classroom.profile.photos',
        'https://www.googleapis.com/auth/classroom.rosters'])
        ->with(['access_type' => 'offline', 'prompt' => 'consent'])
        ->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
            $accessToken = $user->token;
            $refreshToken = $user->refreshToken;
            $expiresIn = $user->expiresIn;
            $refreshExpire = 60;
            Cookie::queue(Cookie::make('google_login_token', $accessToken, $expiresIn, null, null, true, true));
            Cookie::queue(Cookie::make('google_login_refresh_token', $refreshToken, $refreshExpire ,null, null, true ,true));
        } catch (\Exception $e) {
            Log::error('Erro ao autenticar com o Google: ' . $e->getMessage());
            return redirect('/')->with('error', 'Erro ao autenticar com o Google.');
        }

        $existingUser = Users::where('email', $user->email)->first();

        if ($existingUser) {
            auth()->login($existingUser);
        } else {
            try {
                $newUser = new Users();
                $newUser->email = $user->email;
                $newUser->nome = $user->name;
                $newUser->isadmin = false;
                $newUser->ativo = true;
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


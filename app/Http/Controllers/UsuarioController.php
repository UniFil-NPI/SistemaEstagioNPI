<?php


namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function pegarUsuariosAtivos()
    {
        $users = Users::where('ativo', true)->get();

        return view('professoresadmin', compact('users'));
    }

    public function pegarUsuariosDesativos()
    {
        $users = Users::where('ativo', false)->get();

        return view('professoresdesativados', compact('users'));
    }

    public function desativar($email)
    {
        $user = Users::where('email', $email)->first();

        if ($user) {
            $user->ativo = false;
            $user->save();  
        }

        return pegarUsuariosAtivos();
    }

    public function reativar($email)
    {
        $user = Users::where('email', $email)->first();

        if ($user) {
            $user->ativo = true;
            $user->save();  
        }

        return pegarUsuariosDesativos();
    }

    public function darPrivilegiosAdmin($email)
    {
        $user = Users::where('email', $email)->first();

        if ($user) {
            $user->isadmin = true;
            $user->save();  
        }

        return pegarUsuariosAtivos();
    }

    public function tirarPrivilegiosAdmin($email)
    {
        $user = Users::where('email', $email)->first();

        if ($user) {
            $user->isadmin = false;
            $user->save();  
        }

        return pegarUsuariosAtivos();
    }
    
    public function controlarAlunos($email)
    {
        return 0;
    }
}

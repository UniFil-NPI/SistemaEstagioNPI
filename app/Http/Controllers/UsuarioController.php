<?php


namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function pegarUsuariosAtivos()
    {
        $users = Users::where('ativo', true)->get();

        return $users;
    }

    public function pegarUsuariosAtivosView()
    {
        $users = Users::where('ativo', true)->get();
        return view('professoresadmin',compact('users'));
    }

    public function pegarUsuariosDesativos()
    {
        $users = Users::where('ativo', false)->get();

        return view('professoresdesativados', compact('users'));
    }

    public function desativar($email)
    {
        $desativoCount = Users::where('ativo', true)->count();

        if ($desativoCount <= 1) {
            return redirect('/admin/professoresadmin')->with('alert', 'Não foi possível desativar o usuário. Deve haver pelo menos um usuário ativo.');
        }
        
        $user = Users::where('email', $email)->first();

        if ($user) {
            $user->ativo = false;
            $user->save();  
        }

        return $this->pegarUsuariosAtivosView();
    }

    public function reativar($email)
    {
        $user = Users::where('email', $email)->first();

        if ($user) {
            $user->ativo = true;
            $user->save();  
        }

        return $this->pegarUsuariosDesativos();
    }

    public function darPrivilegiosAdmin($email)
    {
        $user = Users::where('email', $email)->first();

        if ($user) {
            $user->isadmin = true;
            $user->save();  
        }

        return $this->pegarUsuariosAtivosView();
    }

    public function tirarPrivilegiosAdmin($email)
{
    $adminCount = Users::where('isadmin', true)->count();

    if ($adminCount <= 1) {
        return redirect('/admin/professoresadmin')->with('alert', 'Não foi possível remover os privilégios de administrador. Deve haver pelo menos um administrador.');
    }

    $user = Users::where('email', $email)->first();

    if ($user) {
        $user->isadmin = false;
        $user->save();  
    }

    return $this->pegarUsuariosAtivosView();
}

    
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aluno extends Model
{
    use HasFactory;

    protected $table = 'aluno';

    protected $primaryKey = 'email_aluno';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'string';

    protected $fillable = [
        'email_aluno',
        'nome',
        'matricula',
        'etapa',
        'ativo',
        'pendente',
        'email_orientador',
        'id_classroom',
    ];

    function orientador(){
        return $this->belongsTo(Users::class,'email_orientador','email_aluno');
    }

}
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
    ];

    public function classrooms()
    {
        return $this->belongsToMany(Classroom::class, 'aluno_classroom', 'email_aluno', 'id_classroom');
    }

    public function orientador()
    {
        return $this->belongsTo(Users::class, 'email_orientador', 'email_aluno');
    }

    
}

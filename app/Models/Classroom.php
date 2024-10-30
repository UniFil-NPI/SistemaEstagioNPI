<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;

    protected $table = 'classroom';

    protected $primaryKey = 'id_classroom';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_classroom',
        'id_dono',
        'nome',
        'ativo',
    ];

    public function alunos()
    {
        return $this->belongsToMany(Aluno::class, 'aluno_classroom', 'id_classroom', 'email_aluno');
    }

    public function atividades()
    {
        return $this->hasMany(Atividade::class, 'id_classroom', 'id_classroom');
    }

}

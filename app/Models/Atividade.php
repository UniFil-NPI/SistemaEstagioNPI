<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Classroom;


class Atividade extends Model
{
    use HasFactory;

    use HasFactory;

    protected $table = 'atividade';

    protected $primaryKey = 'id_atividade';

    public $incrementing = true;

    public $timestamps = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_atividade',
        'id_api',
        'data_criacao',
        'data_entrega',
        'nota',
        'titulo',
        'id_classroom',
        'email_aluno',
    ];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'id_classroom', 'id_classroom');
    }
    public function aluno()
    {
        return $this->belongsTo(Aluno::class, 'email_aluno', 'email_aluno');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orientacao extends Model
{
    use HasFactory;

    use HasFactory;

    protected $table = 'orientacao';

    protected $primaryKey = 'id_orientacao';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_orientacao',
        'data_orientacao',
        'grau_satisfacao',
        'comparecimento',
        'descricao',
        'email_aluno',
    ];
}

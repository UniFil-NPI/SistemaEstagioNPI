<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RPOD extends Model
{
    use HasFactory;

    use HasFactory;

    protected $table = 'rpod';

    protected $primaryKey = 'id_rpod';

    public $incrementing = true;

    public $timestamps = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_rpod',
        'data_entrega',
        'base_64',
        'email_aluno',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HorarioFuncionamento extends Model
{
    protected $table = 'horarios_funcionamento';

    protected $fillable = [
        'id_funcionario',
        'dia_semana',
        'abertura_manha',
        'fechamento_manha',
        'abertura_tarde',
        'fechamento_tarde',
    ];

    public function funcionario()
    {
        return $this->belongsTo(User::class, 'id_funcionario');
    }
}

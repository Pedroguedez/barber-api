<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Agendamento extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'barbeiro_id',
        'plano_id',
        'data',
        'horario',
        'status',
        'observacao',
    ];

    public function cliente()
    {
        return $this->belongsTo(User::class, 'cliente_id');
    }

    public function barbeiro()
    {
        return $this->belongsTo(User::class, 'barbeiro_id');
    }

}

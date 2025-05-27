<?php

namespace App\Models\Unp;

use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    protected $fillable = [
        'nome',
        'unidade',
        'dia_hora',
        'professor',
        'carga',
        'reeducandos', // Adicionado aqui
        'inicio',
        'fim',
        'formatura',
        'status',
    ];

    protected $casts = [
        'inicio' => 'date',
        'fim' => 'date',
        'formatura' => 'date',
    ];
}

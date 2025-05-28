<?php

namespace App\Models\Evento;

use Illuminate\Database\Eloquent\Model;

class Terreiro extends Model
{
    protected $table = 'terreiros';
    protected $fillable = [
        'nome',
        'contato',
        'bairro',
        'terreiro',
        'convidados',
        'onibus',
        'bloco',
        'iurd',
        'pastor',
        'telefone',
        'endereco',
        'localização',
        'confirmado' // Novo campo
    ];
}

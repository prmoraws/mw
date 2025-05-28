<?php

namespace App\Models\Evento;

use Illuminate\Database\Eloquent\Model;

class Cesta extends Model
{
    protected $fillable = [
        'nome',
        'terreiro',
        'contato',
        'cestas',
        'observacao',
        'foto'
    ];

    public function terreiro()
    {
        return $this->belongsTo(Terreiro::class, 'nome', 'nome');
    }
}

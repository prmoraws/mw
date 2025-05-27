<?php

namespace App\Models\Unp;

use Illuminate\Database\Eloquent\Model;

class Presidio extends Model
{
    protected $fillable = [
        'nome',
        'diretor',
        'contato_diretor',
        'adjunto',
        'contato_adjunto',
        'laborativa',
        'contato_laborativa',
        'visita',
        'interno',
    ];
}
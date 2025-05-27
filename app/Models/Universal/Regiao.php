<?php

namespace App\Models\Universal;

use Illuminate\Database\Eloquent\Model;

class Regiao extends Model
{
    protected $fillable = [
        'nome',
        'bloco_id'
    ];

    // public function pessoas()
    // {
    //     return $this->hasMany(Pessoa::class); // Corrigido de 'hashMany' para 'hasMany'
    // }

    // public function igrejas()
    // {
    //     return $this->hasMany(Igreja::class); // Corrigido de 'hashMany' para 'hasMany'
    // }

    public function bloco() // Corrigido de 'blocos' para 'bloco'
    {
        return $this->belongsTo(Bloco::class);
    }
}

<?php

namespace App\Models\Universal;

use Illuminate\Database\Eloquent\Model;

class Igreja extends Model
{
    protected $fillable = [
        'nome',
        'bloco_id',
        'regiao_id'
    ];

    public function pessoas()
    {
        return $this->hasMany(Pessoa::class); // Corrigido de 'hashMany' para 'hasMany'
    }

    public function regiao() // Corrigido de 'regiaos' para 'regiao'
    {
        return $this->belongsTo(Regiao::class);
    }

    public function bloco() // Corrigido de 'blocos' para 'bloco'
    {
        return $this->belongsTo(Bloco::class);
    }

    public function pastores()
    {
        return $this->hasMany(Pastor::class, 'igreja_id');
    }
}

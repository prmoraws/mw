<?php

namespace App\Models\Universal;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $fillable = [
        'nome',
        'descricao',
    ];

    public function pessoas()
    {
        return $this->hashMany(Pessoa::class);
    }
}

<?php

namespace App\Models\Unp;

use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    protected $fillable = [
        'nome',
        'descricao',
    ];

    // public function pessoas()
    // {
    //     return $this->hashMany(Pessoa::class);
    // }
}

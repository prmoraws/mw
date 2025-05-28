<?php

namespace App\Models\Universal;

use Illuminate\Database\Eloquent\Model;

class Bloco extends Model
{
    protected $fillable = [
        'nome'
    ];


    // public function pessoas()
    // {
    //     return $this->hashMany(Pessoa::class);
    // }

    // public function regiaos()
    // {
    //     return $this->hashMany(Regiao::class);
    // }

    // public function igrejas()
    // {
    //     return $this->hashMany(Igreja::class);
    // }
}

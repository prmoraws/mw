<?php

namespace App\Models\Unp;

use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    protected $fillable = [
        'nome'
    ];

    // public function pessoas()
    // {
    //     return $this->hashMany(Pessoa::class);
    // }
}

<?php

namespace App\Models\Universal;

use Illuminate\Database\Eloquent\Model;

class Pastor extends Model
{
    protected $table = 'pastores'; // Garante que o nome da tabela seja 'pastores'
    protected $fillable = [
        'sede',
        'pastor',
        'telefone',
        'esposa',
        'tel_epos',
    ];

    public function igreja()
    {
        return $this->belongsTo(Igreja::class, 'igreja_id');
    }
}

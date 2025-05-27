<?php

namespace App\Models\Unp;

use Illuminate\Database\Eloquent\Model;

class Reeducando extends Model
{
    protected $fillable = ['nome', 'curso_id', 'documento', 'carga', 'observacoes'];

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }
}

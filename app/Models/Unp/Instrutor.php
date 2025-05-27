<?php

namespace App\Models\Unp;

use App\Models\Universal\Bloco;
use App\Models\Universal\Categoria;
use Illuminate\Database\Eloquent\Model;

class Instrutor extends Model
{
    protected $table = 'instrutores'; // Definir explicitamente o nome da tabela

    protected $fillable = [
        'bloco_id',
        'categoria_id',
        'foto',
        'nome',
        'telefone',
        'igreja',
        'profissao',
        'batismo',
        'testemunho',
        'carga',
        'certificado',
        'inscricao',
    ];

    // Remover a configuração de $dates ou $casts para batismo
    // protected $dates = ['batismo'];

    // Tratar batismo como um array JSON
    protected $casts = [
        'batismo' => 'array',
    ];

    public function bloco()
    {
        return $this->belongsTo(Bloco::class);
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
}

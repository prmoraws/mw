<?php

namespace App\Models\Universal;

use App\Models\Cidade;
use App\Models\Estado;
use App\Models\Unp\Cargo;
use App\Models\Unp\Grupo;
use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model
{
    protected $fillable = [
        'bloco_id',
        'regiao_id',
        'igreja_id',
        'categoria_id',
        'cargo_id',
        'grupo_id',
        'cidade_id',
        'estado_id',
        'foto',
        'nome',
        'celular',
        'telefone',
        'email',
        'endereco',
        'bairro',
        'cep',
        'profissao',
        'aptidoes',
        'conversao',
        'obra',
        'trabalho',
        'batismo',
        'preso',
        'testemunho',
    ];

    protected $casts = [
        'trabalho' => 'array',
        'batismo' => 'array',
        'preso' => 'array',
    ];

    public function bloco()
    {
        return $this->belongsTo(Bloco::class);
    }

    public function regiao()
    {
        return $this->belongsTo(Regiao::class);
    }

    public function igreja()
    {
        return $this->belongsTo(Igreja::class);
    }

    // Opcional: outras relações possíveis
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function cargo()
    {
        return $this->belongsTo(Cargo::class);
    }

    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }

    public function cidade()
    {
        return $this->belongsTo(Cidade::class);
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class);
    }
}

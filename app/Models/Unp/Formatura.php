<?php

namespace App\Models\Unp;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Formatura extends Model
{
    protected $fillable = [
        'presidio_id',
        'curso_id',
        'instrutor_id',
        'inicio',
        'fim',
        'formatura',
        'lista',
        'conteudo',
        'oficio',
    ];

    /**
     * Relacionamento com a tabela presidios.
     *
     * @return BelongsTo
     */
    public function presidio(): BelongsTo
    {
        return $this->belongsTo(Presidio::class, 'presidio_id', 'id');
    }

    /**
     * Relacionamento com a tabela cursos.
     *
     * @return BelongsTo
     */
    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

    /**
     * Relacionamento com a tabela instrutores.
     *
     * @return BelongsTo
     */
    public function instrutor(): BelongsTo
    {
        return $this->belongsTo(Instrutor::class);
    }
}
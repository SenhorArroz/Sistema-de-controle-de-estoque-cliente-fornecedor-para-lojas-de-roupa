<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contato extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'tipo',
        'titulo',
        'contato',
        'observacao',
    ];

    /**
     * Define o relacionamento inverso: um contato pertence a um cliente.
     */
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }
}

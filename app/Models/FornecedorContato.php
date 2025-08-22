<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FornecedorContato extends Model
{
    use HasFactory;

    protected $table = 'fornecedor_contatos';

    protected $fillable = [
        'fornecedor_id',
        'tipo',
        'titulo',
        'contato',
        'observacao',
    ];

    public function fornecedor(): BelongsTo
    {
        return $this->belongsTo(Fornecedor::class);
    }
}

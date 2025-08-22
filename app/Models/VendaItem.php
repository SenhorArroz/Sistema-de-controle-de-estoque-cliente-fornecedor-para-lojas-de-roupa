<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendaItem extends Model
{
    protected $fillable = [
        'venda_id', 'produto_id', 'variacao_id', 'codigo_barra_id', 'quantidade', 'preco_unitario',
    ];

    public function venda()
    {
        return $this->belongsTo(Venda::class);
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }

    public function variacao()
    {
        return $this->belongsTo(Variacao::class);
    }

    public function codigoBarra()
    {
        return $this->belongsTo(CodigoBarra::class);
    }
}

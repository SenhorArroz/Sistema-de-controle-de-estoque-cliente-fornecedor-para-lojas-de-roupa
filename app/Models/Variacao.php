<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Variacao extends Model
{
    use SoftDeletes;

    protected $table = 'variacoes';

    protected $fillable = [
        'produto_id', 'tamanho_id', 'cor_id', 'sku', 'valor_compra', 'valor_venda',
        'quantidade', 'estoque_minimo', 'ativo',
    ];

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }

    public function tamanho()
    {
        return $this->belongsTo(Tamanho::class);
    }

    public function cor()
    {
        return $this->belongsTo(Cor::class);
    }

    public function categorias()
    {
        return $this->belongsToMany(Categoria::class, 'categoria_variacao');
    }

    public function codigosBarras()
    {
        return $this->hasMany(CodigoBarra::class);
    }
}

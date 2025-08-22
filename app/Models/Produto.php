<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produto extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'nome', 'fornecedor_id', 'slug', 'descricao', 'peso', 'qtd_estoque', 'ativo',
    ];

    // Relações

    public function categorias()
    {
        return $this->belongsToMany(Categoria::class, 'categoria_produto');
    }

    public function variacoes()
    {
        return $this->hasMany(Variacao::class);
    }

    public function fotos()
    {
        return $this->hasMany(ProdutoFoto::class);
    }

    public function fornecedores()
    {
        return $this->belongsToMany(Fornecedor::class, 'fornecedor_produto')
            ->withPivot('preco_fornecedor', 'sku_fornecedor', 'prazo_entrega_dias');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fornecedor extends Model
{
    use SoftDeletes;
    protected $table = 'fornecedores';

    protected $fillable = [
        'tipo', 'nome', 'documento', 'email', 'telefone', 'observacao'
    ];

    public function enderecos()
    {
        return $this->hasMany(FornecedorEndereco::class);
    }

    public function contatos()
    {
        return $this->hasMany(FornecedorContato::class);
    }

    public function produtos()
    {
        return $this->belongsToMany(Produto::class, 'fornecedor_produto')
            ->withPivot('preco_fornecedor', 'sku_fornecedor', 'prazo_entrega_dias');
    }
}

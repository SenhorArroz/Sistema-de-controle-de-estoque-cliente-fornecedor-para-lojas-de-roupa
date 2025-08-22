<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $fillable = ['titulo'];

    public function produtos()
    {
        return $this->belongsToMany(Produto::class, 'categoria_produto');
    }

    public function variacoes()
    {
        return $this->belongsToMany(Variacao::class, 'categoria_variacao');
    }
}

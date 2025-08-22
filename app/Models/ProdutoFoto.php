<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdutoFoto extends Model
{
    protected $fillable = ['produto_id', 'caminho_foto', 'alt_text', 'ordem', 'is_principal'];

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }
}

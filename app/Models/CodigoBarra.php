<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CodigoBarra extends Model
{
    protected $fillable = ['variacao_id', 'codigo_barra'];
    protected $table = 'codigos_barras';

    public function variacao()
    {
        return $this->belongsTo(Variacao::class);
    }
}

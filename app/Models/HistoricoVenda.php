<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoricoVenda extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'venda_id', 'dados_venda', 'data_backup',
    ];

    protected $casts = [
        'dados_venda' => 'array',
        'data_backup' => 'datetime',
    ];

    public function venda()
    {
        return $this->belongsTo(Venda::class);
    }
}

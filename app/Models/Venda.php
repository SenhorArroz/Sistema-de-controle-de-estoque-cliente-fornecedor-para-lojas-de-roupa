<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venda extends Model
{
    protected $fillable = [
        'cliente_id', 'data_venda', 'pago',
    ];

    protected $casts = [
        'data_venda' => 'datetime',
        'pago' => 'boolean',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function itens()
    {
        return $this->hasMany(VendaItem::class);
    }
}

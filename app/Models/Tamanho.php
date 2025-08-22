<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tamanho extends Model
{
    protected $fillable = ['nome'];

    public function variacoes()
    {
        return $this->hasMany(Variacao::class);
    }
}

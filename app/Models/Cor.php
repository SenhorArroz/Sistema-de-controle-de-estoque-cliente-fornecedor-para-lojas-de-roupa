<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cor extends Model
{
    protected $fillable = ['nome'];
    protected $table = 'cores';

    public function variacoes()
    {
        return $this->hasMany(Variacao::class);
    }
}

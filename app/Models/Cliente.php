<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome_completo',
        'cpf_cnpj',
        'data_nascimento',
        'imagem_perfil_path',
        'observacao',
    ];

    public function enderecos(): HasMany
    {
        return $this->hasMany(Endereco::class);
    }

    public function contatos(): HasMany
    {
        return $this->hasMany(Contato::class);
    }

    public function movimentos(): HasMany
    {
        return $this->hasMany(Movimento::class);
    }
}

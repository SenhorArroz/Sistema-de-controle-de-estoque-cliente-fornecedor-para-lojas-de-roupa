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

    /**
     * Define o relacionamento de um cliente com seus muitos endereços.
     */
    public function enderecos(): HasMany
    {
        return $this->hasMany(Endereco::class);
    }

    /**
     * Define o relacionamento de um cliente com seus muitos contatos.
     */
    public function contatos(): HasMany
    {
        return $this->hasMany(Contato::class);
    }

    /**
     * Define o relacionamento de um cliente com seus muitos movimentos (vendas).
     */
    public function movimentos(): HasMany
    {
        return $this->hasMany(Movimento::class);
    }
}

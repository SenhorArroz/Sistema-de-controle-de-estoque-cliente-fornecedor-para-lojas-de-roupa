<?php

namespace Database\Factories;

use App\Models\Categoria; // 👈 Importe o model Categoria
use App\Models\Produto;   // 👈 Importe o model Produto
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produto>
 */
class ProdutoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome' => $this->faker->words(3, true),
            'slug' => $this->faker->unique()->slug(),
            // Garanta que você tenha um FornecedorSeeder ou crie fornecedores aqui
            'fornecedor_id' => $this->faker->numberBetween(1, 2),
            'descricao' => $this->faker->sentence(),
            'peso' => $this->faker->randomFloat(2, 0.1, 80),
            'qtd_estoque' => $this->faker->numberBetween(0, 100),
            'ativo' => $this->faker->boolean(),
        ];
    }

    /**
     * Indica que, após a criação, o produto deve ser associado a categorias.
     */
    public function configure()
    {
        return $this->afterCreating(function (Produto $produto) {
            // Pega de 1 a 3 IDs de categorias aleatórias que já existem no banco
            $categorias = Categoria::inRandomOrder()
                ->limit($this->faker->numberBetween(1, 3))
                ->pluck('id');

            // Sincroniza as categorias com o produto na tabela pivô
            $produto->categorias()->sync($categorias);
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fornecedores', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo', ['pessoa_fisica', 'pessoa_juridica']);
            $table->string('nome');
            $table->string('documento', 20)->nullable()->unique();
            $table->text('observacao')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('fornecedor_enderecos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fornecedor_id')->constrained('fornecedores')->onDelete('cascade');
            $table->string('rua');
            $table->string('numero', 20);
            $table->string('bairro');
            $table->string('complemento')->nullable();
            $table->string('cidade');
            $table->string('estado', 2);
            $table->string('cep', 9);
            $table->timestamps();
        });

        Schema::create('fornecedor_contatos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fornecedor_id')->constrained('fornecedores')->onDelete('cascade');
            $table->enum('tipo', ['email', 'telefone']);
            $table->string('titulo');
            $table->string('contato');
            $table->text('observacao')->nullable();
            $table->timestamps();
        });

        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->string('titulo')->unique();
            $table->timestamps();
        });

        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('slug')->unique()->nullable();
            $table->foreignId('fornecedor_id')->constrained('fornecedores')->onDelete('cascade');
            $table->text('descricao')->nullable();
            $table->decimal('peso', 8, 3)->nullable()->comment('Peso em kg');
            $table->integer('qtd_estoque')->default(0);
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('produto_fotos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produto_id')->constrained()->onDelete('cascade');
            $table->string('caminho_foto');
            $table->string('alt_text')->nullable();
            $table->integer('ordem')->default(0);
            $table->boolean('is_principal')->default(false);
            $table->timestamps();
        });

        Schema::create('cores', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->unique();
            $table->timestamps();
        });

        Schema::create('tamanhos', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->unique();
            $table->timestamps();
        });

        Schema::create('variacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produto_id')->constrained()->onDelete('cascade');
            $table->foreignId('tamanho_id')->constrained('tamanhos')->onDelete('cascade');
            $table->foreignId('cor_id')->constrained('cores')->onDelete('cascade');
            $table->decimal('valor_compra', 10, 2);
            $table->decimal('valor_venda', 10, 2);
            $table->integer('quantidade')->default(0);
            $table->integer('estoque_minimo')->default(0);
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['produto_id', 'tamanho_id', 'cor_id']);
        });

        Schema::create('codigos_barras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('variacao_id')->constrained('variacoes')->onDelete('cascade');
            $table->string('codigo_barra')->unique();
            $table->timestamps();
        });

        Schema::create('categoria_variacao', function (Blueprint $table) {
            $table->foreignId('categoria_id')->constrained()->onDelete('cascade');
            $table->foreignId('variacao_id')->constrained('variacoes')->onDelete('cascade');
            $table->primary(['categoria_id', 'variacao_id']);
        });

        Schema::create('categoria_produto', function (Blueprint $table) {
            $table->foreignId('categoria_id')->constrained()->onDelete('cascade');
            $table->foreignId('produto_id')->constrained()->onDelete('cascade');
            $table->primary(['categoria_id', 'produto_id']);
        });

        Schema::create('fornecedor_produto', function (Blueprint $table) {
            $table->foreignId('fornecedor_id')->constrained('fornecedores')->onDelete('cascade');
            $table->foreignId('produto_id')->constrained()->onDelete('cascade');
            $table->decimal('preco_fornecedor', 10, 2)->nullable();
            $table->string('sku_fornecedor')->nullable();
            $table->integer('prazo_entrega_dias')->nullable();
            $table->primary(['fornecedor_id', 'produto_id']);
        });

        Schema::create('vendas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->timestamp('data_venda')->useCurrent();
            $table->boolean('pago')->default(false);
            $table->timestamps();
        });

        Schema::create('venda_itens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venda_id')->constrained('vendas')->onDelete('cascade');
            $table->foreignId('produto_id')->constrained('produtos')->onDelete('cascade');
            $table->foreignId('variacao_id')->nullable()->constrained('variacoes')->nullOnDelete();
            $table->foreignId('codigo_barra_id')->nullable()->constrained('codigos_barras')->nullOnDelete();
            $table->integer('quantidade');
            $table->decimal('preco_unitario', 10, 2);
            $table->timestamps();
        });

        Schema::create('historico_vendas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venda_id')->nullable()->constrained('vendas')->nullOnDelete();
            $table->json('dados_venda');
            $table->timestamp('data_backup')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historico_vendas');
        Schema::dropIfExists('venda_itens');
        Schema::dropIfExists('vendas');
        Schema::dropIfExists('fornecedor_produto');
        Schema::dropIfExists('categoria_variacao');
        Schema::dropIfExists('codigos_barras');
        Schema::dropIfExists('variacoes');
        Schema::dropIfExists('tamanhos');
        Schema::dropIfExists('cores');
        Schema::dropIfExists('produto_fotos');
        Schema::dropIfExists('produtos');
        Schema::dropIfExists('categorias');
        Schema::dropIfExists('fornecedor_contatos');
        Schema::dropIfExists('fornecedor_enderecos');
        Schema::dropIfExists('fornecedores');
    }
};

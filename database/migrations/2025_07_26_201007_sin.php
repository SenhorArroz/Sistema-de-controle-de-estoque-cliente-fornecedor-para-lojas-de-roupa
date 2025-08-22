<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nome_completo');
            $table->string('cpf_cnpj')->unique();
            $table->date('data_nascimento')->nullable();
            $table->string('imagem_perfil_path')->nullable();
            $table->text('observacao')->nullable();
            $table->timestamps();
        });

        Schema::create('enderecos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')
                  ->constrained('clientes') // Aponta para a tabela 'clientes'
                  ->onDelete('cascade'); // Se o cliente for deletado, seus endereços também serão.
            $table->string('rua');
            $table->string('numero', 20);
            $table->string('bairro');
            $table->string('complemento')->nullable();
            $table->string('cidade');
            $table->string('estado', 2);
            $table->string('cep', 9);
            $table->text('observacao')->nullable();
            $table->timestamps();
        });

        Schema::create('contatos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')
                  ->constrained('clientes')
                  ->onDelete('cascade');
            $table->enum('tipo', ['email', 'telefone']);
            $table->string('titulo');
            $table->string('contato');
            $table->text('observacao')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
        Schema::dropIfExists('enderecos');
        Schema::dropIfExists('contatos');
    }
};

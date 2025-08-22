<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginSistem;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EnderecoController;
use App\Http\Controllers\ContatoController;
use App\Http\Controllers\FornecedorController;
use App\Http\Controllers\FornecedorEnderecoController;
use App\Http\Controllers\FornecedorContatoController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\VariacaoController;
use App\Http\Controllers\CodigoBarraController;
use App\Http\Controllers\CorController;
use App\Http\Controllers\TamanhoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\VendaController;

Route::get('/', function () {
    return view('welcome');
})->name('login');
Route::get('/dashboard', [SiteController::class, 'dashboard'])->name('dashboard')->middleware('auth');

Route::post('/login', [LoginSistem::class, 'login'])->name('login.post');
Route::resource('clientes', ClienteController::class);
Route::prefix('clientes/{cliente}')->name('clientes.')->group(function () {
    Route::post('enderecos', [EnderecoController::class, 'store'])->name('enderecos.store');
    Route::put('enderecos/{endereco}', [EnderecoController::class, 'update'])->name('enderecos.update');
    Route::delete('enderecos/{endereco}', [EnderecoController::class, 'destroy'])->name('enderecos.destroy');

    Route::post('contatos', [ContatoController::class, 'store'])->name('contatos.store');
    Route::put('contatos/{contato}', [ContatoController::class, 'update'])->name('contatos.update');
    Route::delete('contatos/{contato}', [ContatoController::class, 'destroy'])->name('contatos.destroy');
});
Route::resource('fornecedores', FornecedorController::class);

// Rotas aninhadas para o CRUD de Endereços e Contatos de um Fornecedor
Route::prefix('fornecedores/{fornecedor}')->name('fornecedores.')->group(function () {
    // Endereços
    Route::post('enderecos', [FornecedorEnderecoController::class, 'store'])->name('enderecos.store');
    Route::put('enderecos/{endereco}', [FornecedorEnderecoController::class, 'update'])->name('enderecos.update');
    Route::delete('enderecos/{endereco}', [FornecedorEnderecoController::class, 'destroy'])->name('enderecos.destroy');

    // Contatos
    Route::post('contatos', [FornecedorContatoController::class, 'store'])->name('contatos.store');
    Route::put('contatos/{contato}', [FornecedorContatoController::class, 'update'])->name('contatos.update');
    Route::delete('contatos/{contato}', [FornecedorContatoController::class, 'destroy'])->name('contatos.destroy');
});


Route::resource('produtos', ProdutoController::class);
Route::get('/produtos/search', [ProdutoController::class, 'search'])->name('produtos.search');
Route::resource('variacoes', VariacaoController::class)->except(['edit', 'show', 'create']);
Route::resource('codigos_barras', CodigoBarraController::class)->except(['edit', 'show', 'create']);

Route::prefix('variacoes/{variacao}/codigos')->name('variacoes.codigos.')->group(function () {
    Route::get('/', [CodigoBarraController::class, 'index'])->name('index');
    Route::post('/', [CodigoBarraController::class, 'store'])->name('store');
    Route::delete('/{codigo}', [CodigoBarraController::class, 'destroy'])->name('destroy');
});

Route::resource('cores', CorController::class)->except(['show', 'edit', 'create']);
Route::resource('tamanhos', TamanhoController::class)->except(['show', 'edit', 'create']);

Route::resource('categorias', CategoriaController::class)->only(['index', 'store', 'update', 'destroy']);

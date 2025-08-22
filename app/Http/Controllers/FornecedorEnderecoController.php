<?php

namespace App\Http\Controllers;

use App\Models\Fornecedor;
use App\Models\FornecedorEndereco;
use Illuminate\Http\Request;

class FornecedorEnderecoController extends Controller
{
    public function store(Request $request, Fornecedor $fornecedor)
    {
        $validated = $request->validate([
            'rua' => 'required|string|max:255',
            'numero' => 'required|string|max:20',
            'bairro' => 'required|string|max:100',
            'cidade' => 'required|string|max:100',
            'estado' => 'required|string|max:2',
            'cep' => 'required|string|max:9',
            'complemento' => 'nullable|string|max:255',
        ]);

        $fornecedor->enderecos()->create($validated);
        return redirect()->route('fornecedores.show', $fornecedor->id)->with('success', 'Endereço adicionado com sucesso!');
    }

    public function update(Request $request, Fornecedor $fornecedor, FornecedorEndereco $endereco)
    {
        $validated = $request->validate([
            'rua' => 'required|string|max:255',
            'numero' => 'required|string|max:20',
            'bairro' => 'required|string|max:100',
            'cidade' => 'required|string|max:100',
            'estado' => 'required|string|max:2',
            'cep' => 'required|string|max:9',
            'complemento' => 'nullable|string|max:255',
        ]);

        $endereco->update($validated);
        return redirect()->route('fornecedores.show', $fornecedor->id)->with('success', 'Endereço atualizado com sucesso!');
    }

    public function destroy(Fornecedor $fornecedor, FornecedorEndereco $endereco)
    {
        $endereco->delete();
        return redirect()->route('fornecedores.show', $fornecedor->id)->with('success', 'Endereço excluído com sucesso!');
    }
}

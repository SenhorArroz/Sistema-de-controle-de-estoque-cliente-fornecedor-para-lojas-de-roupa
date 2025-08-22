<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Endereco;
use Illuminate\Http\Request;

class EnderecoController extends Controller
{
    /**
     * Armazena um novo endereço para um cliente.
     */
    public function store(Request $request, Cliente $cliente)
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

        $cliente->enderecos()->create($validated);

        return redirect()->route('clientes.show', $cliente->id)->with('success', 'Endereço adicionado com sucesso!');
    }

    /**
     * Atualiza um endereço específico.
     * A assinatura do método foi corrigida para incluir o Cliente.
     */
    public function update(Request $request, Cliente $cliente, Endereco $endereco)
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

        return redirect()->route('clientes.show', $cliente->id)->with('success', 'Endereço atualizado com sucesso!');
    }

    /**
     * Remove um endereço específico.
     * A assinatura do método foi corrigida para incluir o Cliente.
     */
    public function destroy(Cliente $cliente, Endereco $endereco)
    {
        $endereco->delete();

        return redirect()->route('clientes.show', $cliente->id)->with('success', 'Endereço excluído com sucesso!');
    }
}

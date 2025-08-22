<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Contato;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ContatoController extends Controller
{
    /**
     * Armazena um novo contato para um cliente.
     */
    public function store(Request $request, Cliente $cliente)
    {
        $validated = $request->validate([
            'tipo' => ['required', Rule::in(['email', 'telefone'])],
            'titulo' => 'required|string|max:100',
            'contato' => 'required|string|max:255',
        ]);

        $cliente->contatos()->create($validated);

        return redirect()->route('clientes.show', $cliente->id)->with('success', 'Contato adicionado com sucesso!');
    }

    /**
     * Atualiza um contato específico.
     * A assinatura do método foi corrigida para incluir o Cliente.
     */
    public function update(Request $request, Cliente $cliente, Contato $contato)
    {
        $validated = $request->validate([
            'tipo' => ['required', Rule::in(['email', 'telefone'])],
            'titulo' => 'required|string|max:100',
            'contato' => 'required|string|max:255',
        ]);

        $contato->update($validated);

        return redirect()->route('clientes.show', $cliente->id)->with('success', 'Contato atualizado com sucesso!');
    }

    /**
     * Remove um contato específico.
     * A assinatura do método foi corrigida para incluir o Cliente.
     */
    public function destroy(Cliente $cliente, Contato $contato)
    {
        $contato->delete();

        return redirect()->route('clientes.show', $cliente->id)->with('success', 'Contato excluído com sucesso!');
    }
}

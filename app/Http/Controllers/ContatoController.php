<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Contato;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ContatoController extends Controller
{
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

    public function destroy(Cliente $cliente, Contato $contato)
    {
        $contato->delete();

        return redirect()->route('clientes.show', $cliente->id)->with('success', 'Contato excluído com sucesso!');
    }
}

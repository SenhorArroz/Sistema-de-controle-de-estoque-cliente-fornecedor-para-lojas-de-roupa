<?php

namespace App\Http\Controllers;

use App\Models\Fornecedor;
use App\Models\FornecedorContato;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FornecedorContatoController extends Controller
{
    public function store(Request $request, Fornecedor $fornecedor)
    {
        $validated = $request->validate([
            'tipo' => ['required', Rule::in(['email', 'telefone'])],
            'titulo' => 'required|string|max:100',
            'contato' => 'required|string|max:255',
        ]);

        $fornecedor->contatos()->create($validated);
        return redirect()->route('fornecedores.show', $fornecedor->id)->with('success', 'Contato adicionado com sucesso!');
    }

    public function update(Request $request, Fornecedor $fornecedor, FornecedorContato $contato)
    {
        $validated = $request->validate([
            'tipo' => ['required', Rule::in(['email', 'telefone'])],
            'titulo' => 'required|string|max:100',
            'contato' => 'required|string|max:255',
        ]);

        $contato->update($validated);
        return redirect()->route('fornecedores.show', $fornecedor->id)->with('success', 'Contato atualizado com sucesso!');
    }

    public function destroy(Fornecedor $fornecedor, FornecedorContato $contato)
    {
        $contato->delete();
        return redirect()->route('fornecedores.show', $fornecedor->id)->with('success', 'Contato excluído com sucesso!');
    }
}

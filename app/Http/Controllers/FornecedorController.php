<?php

namespace App\Http\Controllers;

use App\Models\Fornecedor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FornecedorController extends Controller
{
    public function index()
    {
        $fornecedores = Fornecedor::latest()->get();
        return view('fornecedores.index', compact('fornecedores'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',
            'tipo' => ['required', Rule::in(['pessoa_fisica', 'pessoa_juridica'])],
            'observacao' => 'nullable|string',
        ]);

        Fornecedor::create($validatedData);

        return redirect()->route('fornecedores.index')->with('success', 'Fornecedor cadastrado com sucesso!');
    }

    public function show($id)
    {
        $fornecedor = Fornecedor::findOrFail($id);

        $fornecedor->load(['enderecos', 'contatos', 'produtos']);
        return view('fornecedores.show', compact('fornecedor'));
    }

    public function update(Request $request, $id)
    {

        $fornecedor = Fornecedor::findOrFail($id);

        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',
            'tipo' => ['required', Rule::in(['pessoa_fisica', 'pessoa_juridica'])],
            'observacao' => 'nullable|string',
        ]);

        $fornecedor->update($validatedData);

        return redirect()->route('fornecedores.show', $fornecedor->id)->with('success', 'Fornecedor atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $fornecedor = Fornecedor::findOrFail($id);

        $fornecedor->delete();
        return redirect()->route('fornecedores.index')->with('success', 'Fornecedor excluído com sucesso!');
    }
}

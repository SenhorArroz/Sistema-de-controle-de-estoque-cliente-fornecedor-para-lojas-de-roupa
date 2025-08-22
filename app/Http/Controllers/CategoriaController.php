<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoriaController extends Controller
{
    // Listar todas categorias
    public function index()
    {
        // Use withCount('produtos') para carregar a contagem de produtos de forma otimizada
        $categorias = Categoria::withCount('produtos')->latest()->get();

        return view('categorias.index', compact('categorias'));
    }


    // Criar nova categoria
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255|unique:categorias,titulo',
        ]);

        Categoria::create([
            'titulo' => $request->titulo,
        ]);

        return redirect()->route('categorias.index')->with('success', 'Categoria criada com sucesso!');
    }
    public function update(Request $request, Categoria $categoria)
    {
        // 1. Valida os dados recebidos.
        $validatedData = $request->validate([
            'titulo' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categorias')->ignore($categoria->id),
            ],
        ]);

        // 2. Atualiza a categoria com os dados validados.
        $categoria->update($validatedData);

        // 3. Redireciona de volta para a lista com uma mensagem de sucesso.
        return redirect()->route('categorias.index')->with('success', 'Categoria atualizada com sucesso!');
    }


    // Excluir categoria
    public function destroy(Categoria $categoria)
    {
        $categoria->delete();
        return redirect()->route('categorias.index')->with('success', 'Categoria excluída com sucesso!');
    }
}

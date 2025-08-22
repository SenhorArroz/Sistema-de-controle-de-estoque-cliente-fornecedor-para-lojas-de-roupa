<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::withCount('produtos')->latest()->get();

        return view('categorias.index', compact('categorias'));
    }


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
        $validatedData = $request->validate([
            'titulo' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categorias')->ignore($categoria->id),
            ],
        ]);

        $categoria->update($validatedData);

        return redirect()->route('categorias.index')->with('success', 'Categoria atualizada com sucesso!');
    }


    public function destroy(Categoria $categoria)
    {
        $categoria->delete();
        return redirect()->route('categorias.index')->with('success', 'Categoria excluída com sucesso!');
    }
}

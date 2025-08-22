<?php

namespace App\Http\Controllers;

use App\Models\Tamanho;
use Illuminate\Http\Request;

class TamanhoController extends Controller
{
    public function index()
    {
        $tamanhos = Tamanho::orderBy('id')->get();
        return view('tamanhos.index', compact('tamanhos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|unique:tamanhos,nome|max:50',
        ]);

        Tamanho::create($request->only('nome'));

        return redirect()->route('tamanhos.index')->with('success', 'Tamanho criado com sucesso!');
    }

    public function update(Request $request, Tamanho $tamanho)
    {
        $request->validate([
            'nome' => 'required|string|unique:tamanhos,nome,' . $tamanho->id . '|max:50',
        ]);

        $tamanho->update($request->only('nome'));

        return redirect()->route('tamanhos.index')->with('success', 'Tamanho atualizado com sucesso!');
    }

    public function destroy(Tamanho $tamanho)
    {
        $tamanho->delete();
        return redirect()->route('tamanhos.index')->with('success', 'Tamanho excluído com sucesso!');
    }
}

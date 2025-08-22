<?php

namespace App\Http\Controllers;

use App\Models\Cor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CorController extends Controller
{
    public function index()
    {
        $cores = Cor::withCount('variacoes')->latest('id')->get();
        return view('cores.index', compact('cores'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nome' => 'required|string|max:255|unique:cores,nome',
        ]);

        Cor::create($validatedData);

        return redirect()->route('cores.index')->with('success', 'Cor criada com sucesso!');
    }

    public function update(Request $request, Cor $cor)
    {
        $validatedData = $request->validate([
            'nome' => ['required', 'string', 'max:255', Rule::unique('cores')->ignore($cor->id)],
        ]);

        $cor->update($validatedData);

        return redirect()->route('cores.index')->with('success', 'Cor atualizada com sucesso!');
    }

    public function destroy(int $cor)
    {
        $cor = Cor::findOrFail($cor);
        if ($cor->variacoes()->count() > 0) {
            return back()->withErrors(['error' => 'Não é possível excluir uma cor que está em uso por variações de produtos.']);
        }

        $cor->delete();

        return redirect()->route('cores.index')->with('success', 'Cor excluída com sucesso!');
    }
}

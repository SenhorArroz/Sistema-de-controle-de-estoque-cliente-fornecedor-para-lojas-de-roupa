<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Categoria;
use App\Models\Cor;
use App\Models\Tamanho;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProdutoController extends Controller
{
    public function index(Request $request)
    {
        $query = Produto::query();

        if ($request->filled('search_term')) {
            $searchTerm = strtolower($request->search_term);
            $query->whereRaw('LOWER(nome) LIKE ?', ['%' . $searchTerm . '%']);
        }

        if ($request->filled('categoria_id')) {
            $query->whereHas('categorias', function ($q) use ($request) {
                $q->where('categoria_id', $request->categoria_id);
            });
        }

        if ($request->filled('cor_id')) {
            $query->whereHas('variacoes', function ($q) use ($request) {
                $q->where('cor_id', $request->cor_id);
            });
        }

        if ($request->filled('tamanho_id')) {
            $query->whereHas('variacoes', function ($q) use ($request) {
                $q->where('tamanho_id', $request->tamanho_id);
            });
        }

        $produtos = $query->with('categorias')->latest()->paginate(15);
        $produtos->appends($request->query());

        $categorias = Categoria::orderBy('titulo')->get();
        $cores = Cor::orderBy('nome')->get();
        $tamanhos = Tamanho::orderBy('nome')->get();

        return view('produtos.index', compact('produtos', 'categorias', 'cores', 'tamanhos'));
    }

    public function search(Request $request)
    {
        $term = $request->get('term');
        if ($term) {
            $searchTerm = strtolower($term);
            $produtos = Produto::whereRaw('LOWER(nome) LIKE ?', ["%{$searchTerm}%"])
                                ->select('id', 'nome')
                                ->take(10)
                                ->get();

            $results = $produtos->map(function ($produto) {
                return [
                    'id' => $produto->id,
                    'label' => $produto->nome,
                    'url' => route('produtos.edit', $produto->id)
                ];
            });

            return response()->json($results);
        }
        return response()->json([]);
    }


    public function store(Request $request)
{
    $request->validate([
        'nome' => 'required|string|max:255',
        'descricao' => 'nullable|string',
        'peso' => 'nullable|numeric|min:0',
        'fornecedor_id' => 'required|exists:fornecedores,id',
        'ativo' => 'boolean',
        'categorias' => 'required|array|min:1',
        'categorias.*' => 'exists:categorias,id',
    ]);

    $produto = new Produto();
    $produto->nome = $request->nome;
    $produto->fornecedor_id = $request->fornecedor_id;
    $produto->slug = Str::slug($request->nome);
    $produto->descricao = $request->descricao;
    $produto->peso = $request->peso;
    $produto->ativo = $request->has('ativo') ? (bool) $request->ativo : true;
    $produto->qtd_estoque = 0;
    $produto->save();

    $produto->categorias()->sync($request->categorias);

    return redirect()->route('produtos.index')->with('success', 'Produto criado com sucesso!');
}


    public function show(Produto $produto)
    {
        $produto->load(['variacoes.categorias', 'variacoes.cor', 'variacoes.tamanho', 'categorias']);
        $categorias = Categoria::all();
        return view('produtos.show', compact('produto', 'categorias'));
    }

    public function update(Request $request, Produto $produto)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'fornecedor_id' => 'required|exists:fornecedores,id',
            'peso' => 'nullable|numeric|min:0',
            'ativo' => 'boolean',
            'categorias' => 'required|array|min:1',
            'categorias.*' => 'exists:categorias,id',
        ]);

        $produto->nome = $request->nome;
        $produto->slug = Str::slug($request->nome);
        $produto->descricao = $request->descricao;
        $produto->peso = $request->peso;
        $produto->ativo = $request->ativo ?? true;
        $produto->fornecedor_id = $request->fornecedor_id;
        $produto->save();

        $produto->categorias()->sync($request->categorias);

        return redirect()->route('produtos.show', $produto)->with('success', 'Produto atualizado com sucesso!');
    }

    public function destroy(Produto $produto)
    {
        $produto->delete();
        return redirect()->route('produtos.index')->with('success', 'Produto excluído com sucesso!');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Variacao;
use App\Models\CodigoBarra;
use Illuminate\Http\Request;

class CodigoBarraController extends Controller
{
    public function index(Variacao $variacao)
    {
        $variacao->load('produto', 'cor', 'tamanho', 'codigosBarras');
        return view('variacoes.codigos.index', compact('variacao'));
    }

    public function store(Request $request, Variacao $variacao)
    {
        $request->validate([
            'novos_codigos' => 'required|string',
        ]);

        $linhas = preg_split('/\r\n|\r|\n/', $request->novos_codigos);
        foreach ($linhas as $linha) {
            $codigo = trim($linha);
            if ($codigo !== '' && !CodigoBarra::where('codigo_barra', $codigo)->exists()) {
                $variacao->codigosBarras()->create(['codigo_barra' => $codigo]);
            }
        }

        return redirect()->route('variacoes.codigos.index', $variacao)->with('success', 'Códigos adicionados com sucesso!');
    }

    public function destroy(Variacao $variacao, CodigoBarra $codigo)
    {
        if ($codigo->variacao_id !== $variacao->id) {
            abort(404);
        }

        $codigo->delete();

        return redirect()->route('variacoes.codigos.index', $variacao)->with('success', 'Código de barra excluído com sucesso!');
    }
}

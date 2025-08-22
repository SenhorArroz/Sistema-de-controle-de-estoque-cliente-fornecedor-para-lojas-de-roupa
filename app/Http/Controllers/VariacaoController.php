<?php

namespace App\Http\Controllers;

use App\Models\Variacao;
use App\Models\Produto;
use App\Models\Cor;
use App\Models\Tamanho;
use App\Models\Categoria;
use App\Models\CodigoBarra;
use Illuminate\Http\Request;

class VariacaoController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'produto_id' => 'required|exists:produtos,id',
            'cor_id' => 'required|exists:cores,id',
            'tamanho_id' => 'required|exists:tamanhos,id',
            'valor_compra' => 'required|numeric|min:0',
            'valor_venda' => 'required|numeric|min:0',
            'quantidade' => 'required|integer|min:0',
            'estoque_minimo' => 'nullable|integer|min:0',
            'ativo' => 'required|boolean',
            'categorias' => 'required|array|min:1',
            'categorias.*' => 'exists:categorias,id',
            'codigos_barras' => 'nullable|string',
        ]);

        $variacao = new Variacao();
        $variacao->produto_id = $request->produto_id;
        $variacao->cor_id = $request->cor_id;
        $variacao->tamanho_id = $request->tamanho_id;
        $variacao->valor_compra = $request->valor_compra;
        $variacao->valor_venda = $request->valor_venda;
        $variacao->quantidade = $request->quantidade;
        $variacao->estoque_minimo = $request->estoque_minimo ?? 0;
        $variacao->ativo = $request->ativo;
        $variacao->save();

        $variacao->categorias()->sync($request->categorias);

        if ($request->codigos_barras) {
            $linhas = preg_split('/\r\n|\r|\n/', $request->codigos_barras);
            foreach ($linhas as $linha) {
                $codigo = trim($linha);
                if ($codigo !== '' && !CodigoBarra::where('codigo_barra', $codigo)->exists()) {
                    $variacao->codigosBarras()->create(['codigo_barra' => $codigo]);
                }
            }
        }

        $this->atualizaEstoqueProduto($variacao->produto);

        return redirect()->route('produtos.show', $variacao->produto_id)->with('success', 'Variação criada com sucesso!');
    }


    public function update(Request $request, int $variacaoId)
    {
        $request->validate([
            'tamanho_id' => 'required|exists:tamanhos,id',
            'cor_id' => 'required|exists:cores,id',
            'valor_compra' => 'required|numeric|min:0',
            'valor_venda' => 'required|numeric|min:0',
            'quantidade' => 'required|integer|min:0',
            'estoque_minimo' => 'required|integer|min:0',
            'ativo' => 'required|boolean',
        ]);

        $variacao = Variacao::findOrFail($variacaoId);
        $variacao->update($request->only([
            'tamanho_id', 'cor_id', 'valor_compra', 'valor_venda', 'quantidade', 'estoque_minimo', 'ativo'
        ]));

        return redirect()->back()->with('success', 'Variação atualizada com sucesso!');
    }

    public function destroy(int $variacao)
    {
        Variacao::destroy($variacao);
        return redirect()->back()->with('success', 'Variação excluída com sucesso!');
    }

    private function atualizaEstoqueProduto(Produto $produto)
    {
        $qtdTotal = $produto->variacoes()->sum('quantidade');
        $produto->qtd_estoque = $qtdTotal;
        $produto->save();
    }
}

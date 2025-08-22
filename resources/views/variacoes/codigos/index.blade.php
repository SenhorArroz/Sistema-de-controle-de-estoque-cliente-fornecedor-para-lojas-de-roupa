@extends('Layouts.app')

@section('content')
<div class="container py-4">

    <h1>Gerenciar Códigos de Barra - Variação #{{ $variacao->id }}</h1>

    <p>
        Produto: <strong>{{ $variacao->produto->nome }}</strong><br>
        Cor: <strong>{{ $variacao->cor->nome }}</strong><br>
        Tamanho: <strong>{{ $variacao->tamanho->nome }}</strong>
    </p>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('variacoes.codigos.store', $variacao) }}" method="POST" class="mb-4">
        @csrf
        <div class="mb-3">
            <label for="novos_codigos" class="form-label">Adicionar novos códigos (um por linha)</label>
            <textarea name="novos_codigos" id="novos_codigos" class="form-control" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Adicionar códigos</button>
    </form>

    <table class="table table-bordered table-sm">
        <thead>
            <tr>
                <th>ID</th>
                <th>Código de Barra</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($variacao->codigosBarras as $codigo)
                <tr>
                    <td>{{ $codigo->id }}</td>
                    <td>{{ $codigo->codigo_barra }}</td>
                    <td>
                        <form action="{{ route('variacoes.codigos.destroy', [$variacao, $codigo]) }}" method="POST" onsubmit="return confirm('Excluir código de barra?');" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Excluir</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            @if($variacao->codigosBarras->isEmpty())
                <tr>
                    <td colspan="3" class="text-center">Nenhum código de barra cadastrado.</td>
                </tr>
            @endif
        </tbody>
    </table>

    <a href="{{ route('produtos.show', $variacao->produto) }}" class="btn btn-secondary mt-3">Voltar ao Produto</a>
</div>
@endsection

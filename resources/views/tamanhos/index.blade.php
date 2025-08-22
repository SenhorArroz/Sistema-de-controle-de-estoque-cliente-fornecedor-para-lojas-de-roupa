@extends('Layouts.app')

@section('content')
<div class="container py-4">
    <h1>Tamanhos</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('tamanhos.store') }}" method="POST" class="mb-3">
        @csrf
        <div class="input-group">
            <input type="text" name="nome" class="form-control" placeholder="Novo tamanho" required>
            <button class="btn btn-primary" type="submit">Adicionar</button>
        </div>
    </form>

    <table class="table table-bordered table-sm">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tamanhos as $tamanho)
                <tr>
                    <td>{{ $tamanho->id }}</td>
                    <td>{{ $tamanho->nome }}</td>
                    <td>
                        <form action="{{ route('tamanhos.destroy', $tamanho) }}" method="POST" onsubmit="return confirm('Excluir tamanho?');" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Excluir</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            @if($tamanhos->isEmpty())
                <tr>
                    <td colspan="3" class="text-center">Nenhum tamanho cadastrado.</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
@endsection

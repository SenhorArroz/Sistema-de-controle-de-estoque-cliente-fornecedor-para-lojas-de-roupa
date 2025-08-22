@extends('layouts.app')

@section('title', 'Detalhes de ' . $fornecedor->nome)

@section('content')
<div class="container py-5">

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Ocorreu um erro!</strong> Por favor, verifique os dados inseridos.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex flex-column flex-md-row align-items-center">
                <div class="flex-grow-1">
                    <h1 class="h3 mb-0">{{ $fornecedor->nome }}</h1>
                    <p class="text-muted mb-1">
                        <span class="badge bg-secondary">{{ $fornecedor->tipo == 'pessoa_juridica' ? 'Pessoa Jurídica' : 'Pessoa Física' }}</span>
                    </p>
                    <p class="text-muted small">Fornecedor desde: {{ $fornecedor->created_at ? $fornecedor->created_at->format('d/m/Y') : 'Data não disponível' }}</p>
                </div>
                <div class="mt-3 mt-md-0">
                    <a href="{{ route('fornecedores.index') }}" class="btn btn-secondary me-2"><i class="bi bi-arrow-left"></i> Voltar</a>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editFornecedorModal"><i class="bi bi-pencil-square"></i> Editar</button>
                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteFornecedorModal"><i class="bi bi-trash"></i> Excluir</button>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Endereços</h5>
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addFornecedorEnderecoModal"><i class="bi bi-plus-circle"></i> Adicionar</button>
                </div>
                <div class="list-group list-group-flush">
                    @forelse($fornecedor->enderecos as $endereco)
                        <div class="list-group-item">
                            <p class="mb-1 fw-bold">{{ $endereco->rua }}, {{ $endereco->numero }} @if($endereco->complemento)- {{ $endereco->complemento }}@endif</p>
                            <p class="mb-1 text-muted">{{ $endereco->bairro }}</p>
                            <p class="mb-1 text-muted">{{ $endereco->cidade }}, {{ $endereco->estado }} - CEP: {{ $endereco->cep }}</p>
                            <div class="mt-2">
                                <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editFornecedorEnderecoModal-{{ $endereco->id }}">Editar</button>
                                <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteFornecedorEnderecoModal-{{ $endereco->id }}">Excluir</button>
                            </div>
                        </div>
                    @empty
                        <div class="list-group-item text-center text-muted">Nenhum endereço registado.</div>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Outros Contatos</h5>
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addFornecedorContatoModal"><i class="bi bi-plus-circle"></i> Adicionar</button>
                </div>
                 <div class="list-group list-group-flush">
                    @forelse($fornecedor->contatos as $contato)
                        <div class="list-group-item">
                            <strong class="d-block">{{ $contato->titulo }} ({{ ucfirst($contato->tipo) }})</strong>
                            <p class="mb-1 text-muted">{{ $contato->contato }}</p>
                            @if($contato->observacao)
                                <p class="mb-1 text-muted fst-italic small">Obs: {{ $contato->observacao }}</p>
                            @endif
                            <div class="mt-2">
                                <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editFornecedorContatoModal-{{ $contato->id }}">Editar</button>
                                <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteFornecedorContatoModal-{{ $contato->id }}">Excluir</button>
                            </div>
                        </div>
                    @empty
                        <div class="list-group-item text-center text-muted">Nenhum contato adicional registado.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Observações Gerais</h5>
            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editFornecedorModal"><i class="bi bi-pencil"></i> Editar</button>
        </div>
        <div class="card-body">
            @if($fornecedor->observacao)
                <p class="card-text" style="white-space: pre-wrap;">{{ $fornecedor->observacao }}</p>
            @else
                <p class="text-muted">Nenhuma observação registada para este fornecedor.</p>
            @endif
        </div>
    </div>
</div>
<div class="modal fade" id="editFornecedorModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('fornecedores.update', $fornecedor->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header"><h5 class="modal-title">Editar Fornecedor</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 mb-3"><label class="form-label">Nome</label><input type="text" class="form-control" name="nome" value="{{ $fornecedor->nome }}" required></div>
                        <div class="col-md-4 mb-3"><label class="form-label">Tipo</label><select class="form-select" name="tipo"><option value="pessoa_juridica" @if($fornecedor->tipo == 'pessoa_juridica') selected @endif>Pessoa Jurídica</option><option value="pessoa_fisica" @if($fornecedor->tipo == 'pessoa_fisica') selected @endif>Pessoa Física</option></select></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3"><label class="form-label">Email</label><input type="email" class="form-control" name="email" value="{{ $fornecedor->email }}"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Telefone</label><input type="text" class="form-control" name="telefone" value="{{ $fornecedor->telefone }}"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Observação</label>
                        <textarea class="form-control" name="observacao" rows="4">{{ $fornecedor->observacao }}</textarea>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button><button type="submit" class="btn btn-primary">Salvar Alterações</button></div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteFornecedorModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('fornecedores.destroy', $fornecedor->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header"><h5 class="modal-title">Confirmar Exclusão</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body"><p>Tem a certeza que deseja excluir o fornecedor <strong>{{ $fornecedor->nome }}</strong>?</p><p class="text-danger">Esta ação não pode ser desfeita.</p></div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button><button type="submit" class="btn btn-danger">Excluir Fornecedor</button></div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="addFornecedorEnderecoModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('fornecedores.enderecos.store', $fornecedor->id) }}" method="POST">
                @csrf
                <div class="modal-header"><h5 class="modal-title">Adicionar Novo Endereço</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 mb-3"><label class="form-label">Rua</label><input type="text" class="form-control" name="rua" required></div>
                        <div class="col-md-4 mb-3"><label class="form-label">Número</label><input type="text" class="form-control" name="numero" required></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Bairro</label><input type="text" class="form-control" name="bairro" required></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Complemento</label><input type="text" class="form-control" name="complemento"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Cidade</label><input type="text" class="form-control" name="cidade" required></div>
                        <div class="col-md-3 mb-3"><label class="form-label">Estado</label><input type="text" class="form-control" name="estado" maxlength="2" required></div>
                        <div class="col-md-3 mb-3"><label class="form-label">CEP</label><input type="text" class="form-control" name="cep" required></div>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button><button type="submit" class="btn btn-primary">Salvar Endereço</button></div>
            </form>
        </div>
    </div>
</div>

@foreach($fornecedor->enderecos as $endereco)
    <div class="modal fade" id="editFornecedorEnderecoModal-{{ $endereco->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('fornecedores.enderecos.update', ['fornecedor' => $fornecedor->id, 'endereco' => $endereco->id]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header"><h5 class="modal-title">Editar Endereço</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-8 mb-3"><label class="form-label">Rua</label><input type="text" class="form-control" name="rua" value="{{ $endereco->rua }}" required></div>
                            <div class="col-md-4 mb-3"><label class="form-label">Número</label><input type="text" class="form-control" name="numero" value="{{ $endereco->numero }}" required></div>
                            <div class="col-md-6 mb-3"><label class="form-label">Bairro</label><input type="text" class="form-control" name="bairro" value="{{ $endereco->bairro }}" required></div>
                            <div class="col-md-6 mb-3"><label class="form-label">Complemento</label><input type="text" class="form-control" name="complemento" value="{{ $endereco->complemento }}"></div>
                            <div class="col-md-6 mb-3"><label class="form-label">Cidade</label><input type="text" class="form-control" name="cidade" value="{{ $endereco->cidade }}" required></div>
                            <div class="col-md-3 mb-3"><label class="form-label">Estado</label><input type="text" class="form-control" name="estado" value="{{ $endereco->estado }}" maxlength="2" required></div>
                            <div class="col-md-3 mb-3"><label class="form-label">CEP</label><input type="text" class="form-control" name="cep" value="{{ $endereco->cep }}" required></div>
                        </div>
                    </div>
                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button><button type="submit" class="btn btn-primary">Salvar Alterações</button></div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteFornecedorEnderecoModal-{{ $endereco->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('fornecedores.enderecos.destroy', ['fornecedor' => $fornecedor->id, 'endereco' => $endereco->id]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header"><h5 class="modal-title">Confirmar Exclusão</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                    <div class="modal-body"><p>Tem a certeza que deseja excluir este endereço?</p><p class="text-muted">{{ $endereco->rua }}, {{ $endereco->numero }}</p></div>
                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button><button type="submit" class="btn btn-danger">Excluir Endereço</button></div>
                </form>
            </div>
        </div>
    </div>
@endforeach

<div class="modal fade" id="addFornecedorContatoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('fornecedores.contatos.store', $fornecedor->id) }}" method="POST">
                @csrf
                <div class="modal-header"><h5 class="modal-title">Adicionar Novo Contato</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">Tipo</label><select class="form-select" name="tipo" required><option value="telefone">Telefone</option><option value="email">Email</option></select></div>
                    <div class="mb-3"><label class="form-label">Título</label><input type="text" class="form-control" name="titulo" placeholder="Ex: Vendas" required></div>
                    <div class="mb-3"><label class="form-label">Contato</label><input type="text" class="form-control" name="contato" required></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button><button type="submit" class="btn btn-primary">Salvar Contato</button></div>
            </form>
        </div>
    </div>
</div>

@foreach($fornecedor->contatos as $contato)
    <div class="modal fade" id="editFornecedorContatoModal-{{ $contato->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('fornecedores.contatos.update', ['fornecedor' => $fornecedor->id, 'contato' => $contato->id]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header"><h5 class="modal-title">Editar Contato</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                    <div class="modal-body">
                        <div class="mb-3"><label class="form-label">Tipo</label><select class="form-select" name="tipo" required><option value="telefone" @if($contato->tipo == 'telefone') selected @endif>Telefone</option><option value="email" @if($contato->tipo == 'email') selected @endif>Email</option></select></div>
                        <div class="mb-3"><label class="form-label">Título</label><input type="text" class="form-control" name="titulo" value="{{ $contato->titulo }}" required></div>
                        <div class="mb-3"><label class="form-label">Contato</label><input type="text" class="form-control" name="contato" value="{{ $contato->contato }}" required></div>
                    </div>
                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button><button type="submit" class="btn btn-primary">Salvar Alterações</button></div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteFornecedorContatoModal-{{ $contato->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('fornecedores.contatos.destroy', ['fornecedor' => $fornecedor->id, 'contato' => $contato->id]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header"><h5 class="modal-title">Confirmar Exclusão</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                    <div class="modal-body"><p>Tem a certeza que deseja excluir este contato?</p><p class="text-muted">{{ $contato->titulo }}: {{ $contato->contato }}</p></div>
                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button><button type="submit" class="btn btn-danger">Excluir Contato</button></div>
                </form>
            </div>
        </div>
    </div>
@endforeach

@endsection

@extends('Layouts.app')

@section('title', 'Gestão de Fornecedores')

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
            <strong>Ocorreu um erro!</strong> Por favor, verifique os dados no formulário.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h1 class="display-6">Gestão de Fornecedores</h1>
            <p class="lead text-muted">Adicione e gerencie os fornecedores dos seus produtos.</p>
        </div>
        <div class="col-md-6 text-md-end">
            <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#addFornecedorModal">
                <i class="bi bi-plus-circle-fill me-2"></i>
                Novo Fornecedor
            </button>
        </div>
    </div>

    <div class="row">
        @forelse ($fornecedores as $fornecedor)
            <div class="col-md-6 col-lg-4 mb-4">
                <a href="{{ route('fornecedores.show', $fornecedor->id) }}" class="card lift h-100 text-decoration-none text-dark">
                    <div class="card-body d-flex align-items-center">
                        <div class="p-3 bg-light rounded-circle me-3">
                             <i class="bi bi-truck fs-2 text-primary"></i>
                        </div>
                        <div>
                            <h6 class="card-title mb-0">{{ $fornecedor->nome }}</h6>
                            <p class="card-text text-muted small mb-0">{{ $fornecedor->email ?? 'Email não informado' }}</p>
                            <span class="badge bg-secondary mt-1">{{ $fornecedor->tipo == 'pessoa_juridica' ? 'Pessoa Jurídica' : 'Pessoa Física' }}</span>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-secondary text-center">
                    <i class="bi bi-info-circle me-2"></i>
                    Nenhum fornecedor registado ainda. Clique em "Novo Fornecedor" para começar.
                </div>
            </div>
        @endforelse
    </div>
</div>

<!-- Modal de Cadastro de Fornecedor -->
<div class="modal fade" id="addFornecedorModal" tabindex="-1" aria-labelledby="addFornecedorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('fornecedores.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addFornecedorModalLabel">Cadastrar Novo Fornecedor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Nome</label>
                            <input type="text" class="form-control @error('nome') is-invalid @enderror" name="nome" value="{{ old('nome') }}" required>
                            @error('nome') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tipo</label>
                            <select class="form-select" name="tipo">
                                <option value="pessoa_juridica" @if(old('tipo') == 'pessoa_juridica') selected @endif>Pessoa Jurídica</option>
                                <option value="pessoa_fisica" @if(old('tipo') == 'pessoa_fisica') selected @endif>Pessoa Física</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Telefone</label>
                            <input type="text" class="form-control" name="telefone" value="{{ old('telefone') }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Observação</label>
                        <textarea class="form-control" name="observacao" rows="3">{{ old('observacao') }}</textarea>
                    </div>
                    <p class="text-muted small">Os endereços e contactos adicionais poderão ser geridos na página de detalhes do fornecedor após o registo inicial.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar Fornecedor</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

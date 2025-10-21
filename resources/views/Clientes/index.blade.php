@extends('Layouts.app')

@section('title', 'Gestão de Clientes')

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
            <h1 class="display-6">Gestão de Clientes</h1>
            <p class="lead text-muted">Visualize, adicione e gerencie os clientes da sua loja.</p>
        </div>
        <div class="col-md-6 text-md-end">
            <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#addClientModal">
                <i class="bi bi-plus-circle-fill me-2"></i>
                Novo Cliente
            </button>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-6">
            <div class="card text-bg-warning h-100">
                <div class="card-body">
                    <h5 class="card-title">Dívidas Ativas</h5>
                    <p class="display-4">{{ 'R$ ' . number_format($totalDividas ?? 0, 2, ',', '.') }}</p>
                    <small>Valor total de vendas em aberto.</small>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card text-bg-info h-100">
                <div class="card-body">
                    <h5 class="card-title">Clientes Registrados</h5>
                    <p class="display-4">{{ $clientes->count() }}</p>
                    <small>Total de clientes na base de dados.</small>
                </div>
            </div>
        </div>
    </div>


    <h2 class="mb-4">Clientes Registrados</h2>
    <div class="row">
        @forelse ($clientes as $cliente)
            <div class="col-md-6 col-lg-4 mb-4">
                <a href="{{ route('clientes.show', $cliente->id) }}" class="card lift h-100 text-decoration-none text-dark">
                    <div class="card-body d-flex align-items-center">
                        <img style="width: 70px; height: 70px; object-fit: cover;" src="{{ $cliente->imagem_perfil_path ? asset('storage/' . $cliente->imagem_perfil_path) : 'https://placehold.co/70x70/EFEFEF/333?text=Foto' }}" class="rounded-circle me-3" alt="Foto de {{ $cliente->nome_completo }}">
                        <div>
                            <h6 class="card-title mb-0">{{ $cliente->nome_completo }}</h6>
                            <p class="card-text text-muted small mb-0">CPF/CNPJ: {{ $cliente->cpf_cnpj }}</p>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-secondary text-center">
                    <i class="bi bi-info-circle me-2"></i>
                    Nenhum cliente registrado ainda. Clique em "Novo Cliente" para começar.
                </div>
            </div>
        @endforelse
    </div>

</div>


<div class="modal fade" id="addClientModal" tabindex="-1" aria-labelledby="addClientModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addClientModalLabel">Cadastrar Novo Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addClientForm" action="{{ route('clientes.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <h6>Dados Pessoais</h6>
                    <hr class="mt-0">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="nome_completo" class="form-label">Nome Completo</label>
                            <input type="text" class="form-control @error('nome_completo') is-invalid @enderror" name="nome_completo" value="{{ old('nome_completo') }}" required>
                            @error('nome_completo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="cpf_cnpj" class="form-label">CPF/CNPJ</label>
                            <input type="text" class="form-control @error('cpf_cnpj') is-invalid @enderror" name="cpf_cnpj" value="{{ old('cpf_cnpj') }}" required>
                            @error('cpf_cnpj')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="imagem_perfil" class="form-label">Foto do Perfil</label>
                        <input class="form-control @error('imagem_perfil') is-invalid @enderror" type="file" name="imagem_perfil">
                        @error('imagem_perfil')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="observacao" class="form-label">Observações</label>
                        <textarea class="form-control" id="observacao" name="observacao" rows="3">{{ old('observacao') }}</textarea>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <h6 class="mb-0">Endereços</h6>
                        <button type="button" id="add-address-btn" class="btn btn-sm btn-outline-primary"><i class="bi bi-plus-circle"></i> Adicionar</button>
                    </div>
                    <hr class="mt-2">
                    <div id="addresses-container">
                        <div class="address-entry border rounded p-3 mb-3">
                            <div class="row">
                                <div class="col-md-8 mb-3"><label class="form-label">Rua</label><input type="text" class="form-control @error('enderecos.0.rua') is-invalid @enderror" name="enderecos[0][rua]"></div>
                                <div class="col-md-4 mb-3"><label class="form-label">Número</label><input type="text" class="form-control" name="enderecos[0][numero]"></div>
                                <div class="col-md-6 mb-3"><label class="form-label">Bairro</label><input type="text" class="form-control" name="enderecos[0][bairro]"></div>
                                <div class="col-md-6 mb-3"><label class="form-label">Complemento</label><input type="text" class="form-control" name="enderecos[0][complemento]"></div>
                                <div class="col-md-6 mb-3"><label class="form-label">Cidade</label><input type="text" class="form-control" name="enderecos[0][cidade]"></div>
                                <div class="col-md-3 mb-3"><label class="form-label">Estado</label><input type="text" class="form-control" name="enderecos[0][estado]" maxlength="2"></div>
                                <div class="col-md-3 mb-3"><label class="form-label">CEP</label><input type="text" class="form-control" name="enderecos[0][cep]"></div>
                            </div>
                            <button type="button" class="btn btn-sm btn-danger remove-address-btn" disabled><i class="bi bi-trash"></i> Remover Endereço</button>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <h6 class="mb-0">Contatos</h6>
                        <button type="button" id="add-contact-btn" class="btn btn-sm btn-outline-primary"><i class="bi bi-plus-circle"></i> Adicionar</button>
                    </div>
                    <hr class="mt-2">
                    <div id="contacts-container">
                        <div class="row contact-entry mb-3 gx-2 align-items-end">
                            <div class="col-md-3"><label class="form-label">Tipo</label><select class="form-select" name="contatos[0][tipo]"><option value="telefone">Telefone</option><option value="email">Email</option></select></div>
                            <div class="col-md-4"><label class="form-label">Título</label><input type="text" class="form-control" name="contatos[0][titulo]" placeholder="Ex: Celular Pessoal"></div>
                            <div class="col-md-4"><label class="form-label">Contato</label><input type="text" class="form-control @error('contatos.0.contato') is-invalid @enderror" name="contatos[0][contato]" placeholder="(99) 99999-9999"></div>
                            <div class="col-md-1"><button type="button" class="btn btn-danger w-100 remove-contact-btn" disabled><i class="bi bi-trash"></i></button></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary" form="addClientForm">Salvar Cliente</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var addModal = new bootstrap.Modal(document.getElementById('addClientModal'));
        addModal.show();
    });
</script>
@endif

<script>
document.addEventListener('DOMContentLoaded', function () {
});
</script>
@endpush

@extends('layouts.app')

@section('title', 'Detalhes de ' . $cliente->nome_completo)

@section('content')
<div class="container py-5">

    {{-- Bloco para exibir mensagens de feedback --}}
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

    <!-- Cabeçalho do Perfil do Cliente -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex flex-column flex-md-row align-items-center">
                <img src="{{ $cliente->imagem_perfil_path ? asset('storage/' . $cliente->imagem_perfil_path) : 'https://placehold.co/100x100/EFEFEF/333?text=Foto' }}"
                     alt="Foto de {{ $cliente->nome_completo }}"
                     class="rounded-circle me-md-4 mb-3 mb-md-0"
                     style="width: 100px; height: 100px; object-fit: cover;">
                <div class="flex-grow-1">
                    <h1 class="h3 mb-0">{{ $cliente->nome_completo }}</h1>
                    <p class="text-muted mb-1">CPF/CNPJ: {{ $cliente->cpf_cnpj }}</p>
                    <p class="text-muted small">Cliente desde: {{ $cliente->created_at->format('d/m/Y') }}</p>
                </div>
                <div class="mt-3 mt-md-0">
                    <a href="{{ route('clientes.index') }}" class="btn btn-secondary me-2"><i class="bi bi-arrow-left"></i> Voltar</a>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editClientModal"><i class="bi bi-pencil-square"></i> Editar</button>
                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteClientModal"><i class="bi bi-trash"></i> Excluir</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Abas de Navegação -->
    <ul class="nav nav-tabs nav-fill mb-4" id="clientTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info-tab-pane" type="button" role="tab" aria-controls="info-tab-pane" aria-selected="true">
                <i class="bi bi-person-lines-fill me-2"></i>Informações
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history-tab-pane" type="button" role="tab" aria-controls="history-tab-pane" aria-selected="false">
                <i class="bi bi-receipt-cutoff me-2"></i>Histórico de Compras
            </button>
        </li>
    </ul>

    <!-- Conteúdo das Abas -->
    <div class="tab-content" id="clientTabContent">
        <div class="tab-pane fade show active" id="info-tab-pane" role="tabpanel" aria-labelledby="info-tab" tabindex="0">
            <div class="row g-4">
                <!-- Endereços -->
                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center"><h5 class="mb-0">Endereços</h5><button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addEnderecoModal"><i class="bi bi-plus-circle"></i> Adicionar</button></div>
                        <div class="list-group list-group-flush">
                            @forelse ($cliente->enderecos as $endereco)
                            <div class="list-group-item"><p class="mb-1 fw-bold">{{ $endereco->rua }}, {{ $endereco->numero }}</p><p class="mb-1 text-muted">{{ $endereco->cidade }}, {{ $endereco->estado }}</p><div class="mt-2"><button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editEnderecoModal-{{ $endereco->id }}">Editar</button><button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteEnderecoModal-{{ $endereco->id }}">Excluir</button></div></div>
                            @empty
                            <div class="list-group-item text-center text-muted">Nenhum endereço registado.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
                <!-- Contatos -->
                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center"><h5 class="mb-0">Contatos</h5><button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addContatoModal"><i class="bi bi-plus-circle"></i> Adicionar</button></div>
                        <div class="list-group list-group-flush">
                            @forelse ($cliente->contatos as $contato)
                            <div class="list-group-item">
                                <strong class="d-block">{{ $contato->titulo }} ({{ ucfirst($contato->tipo) }})</strong>
                                <p class="mb-1 text-muted">{{ $contato->contato }}</p>
                                @if($contato->observacao)
                                    <p class="mb-1 text-muted fst-italic small">Obs: {{ $contato->observacao }}</p>
                                @endif
                                <div class="mt-2">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editContatoModal-{{ $contato->id }}">Editar</button>
                                    <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteContatoModal-{{ $contato->id }}">Excluir</button>
                                </div>
                            </div>
                            @empty
                            <div class="list-group-item text-center text-muted">Nenhum contato registado.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            <!-- Card de Observações do Cliente -->
            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Observações Gerais do Cliente</h5>
                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editClientModal"><i class="bi bi-pencil"></i> Editar</button>
                </div>
                <div class="card-body">
                    @if($cliente->observacao)
                        <p class="card-text" style="white-space: pre-wrap;">{{ $cliente->observacao }}</p>
                    @else
                        <p class="text-muted">Nenhuma observação registada para este cliente.</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="history-tab-pane" role="tabpanel" aria-labelledby="history-tab" tabindex="0">
            <div class="card"><div class="card-body text-center"><p class="text-muted">O histórico de compras aparecerá aqui.</p></div></div>
        </div>
    </div>
</div>

<!-- ======================= INCLUSÃO DOS MODAIS ======================= -->

<!-- Modais para o CRUD do Cliente -->
@include('clientes.modals.edit-cliente')
@include('clientes.modals.delete-cliente')

<!-- Modais para o CRUD de Endereços -->
@include('clientes.modals.add-endereco')
@foreach($cliente->enderecos as $endereco)
    @include('clientes.modals.edit-endereco', ['endereco' => $endereco])
    @include('clientes.modals.delete-endereco', ['endereco' => $endereco])
@endforeach

<!-- Modais para o CRUD de Contactos -->
@include('clientes.modals.add-contato')
@foreach($cliente->contatos as $contato)
    @include('clientes.modals.edit-contato', ['contato' => $contato])
    @include('clientes.modals.delete-contato', ['contato' => $contato])
@endforeach

@endsection

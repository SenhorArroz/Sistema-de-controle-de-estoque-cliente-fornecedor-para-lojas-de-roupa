@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container py-5">

    <div class="row mb-4">
        <div class="col-12">
            <h1 class="display-5">Dashboard</h1>
            <p class="lead text-muted">Bem-vindo(a)! Aqui está um resumo da sua loja.</p>
        </div>
    </div>

    <div class="row g-4">

        <div class="col-md-6 col-lg-4">
            <div class="card text-bg-success h-100 shadow-sm">
                <div class="card-body d-flex flex-column">
                    <div class="mb-2">
                        <i class="bi bi-cash-coin fs-2"></i>
                    </div>
                    <h5 class="card-title">Vendas do Dia</h5>
                    <p class="display-4 fw-bold mt-auto">{{ 'R$ ' . number_format($vendasHoje, 2, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card text-bg-primary h-100 shadow-sm">
                <div class="card-body d-flex flex-column">
                     <div class="mb-2">
                        <i class="bi bi-people-fill fs-2"></i>
                    </div>
                    <h5 class="card-title">Clientes</h5>
                    <p class="display-4 fw-bold mt-auto">{{ $totalClientes }}</p>
                    <a href="{{ route('clientes.index') }}" class="btn btn-light mt-3 stretched-link">
                        Gerenciar Clientes <i class="bi bi-arrow-right-circle"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-lg-4">
            <div class="card text-bg-info h-100 shadow-sm">
                <div class="card-body d-flex flex-column">
                     <div class="mb-2">
                        <i class="bi bi-box-seam-fill fs-2"></i>
                    </div>
                    <h5 class="card-title">Produtos em Estoque</h5>
                    <p class="display-4 fw-bold fs-2">{{ $totalProdutos }}</p>

                    <h5 class="card-title">Produtos registrados</h5>
                    <p class="display-4 fw-bold mt-auto fs-2">{{ $registeredProdutos }}</p>
                    <a href="{{ route('produtos.index') }}" class="btn btn-light mt-3 stretched-link">
                        Ver Estoque <i class="bi bi-arrow-right-circle"></i>
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Produtos</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAddProduto">
            <i class="bi bi-plus-circle me-1"></i> Adicionar Produto
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <h5 class="alert-heading">Ocorreram erros:</h5>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('produtos.index') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label for="search_term" class="form-label">Nome do Produto</label>
                        <div class="position-relative">
                            <input type="text" name="search_term" id="search_term" class="form-control" placeholder="Buscar por nome..." value="{{ request('search_term') }}">
                            <div id="search_results" class="list-group position-absolute w-100" style="z-index: 1000; max-height: 300px; overflow-y: auto;">
                                </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="categoria_id" class="form-label">Categoria</label>
                        <select name="categoria_id" id="categoria_id" class="form-select">
                            <option value="">Todas</option>
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id }}" {{ request('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                    {{ $categoria->titulo }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="cor_id" class="form-label">Cor</label>
                        <select name="cor_id" id="cor_id" class="form-select">
                            <option value="">Todas</option>
                            @foreach($cores as $cor)
                                <option value="{{ $cor->id }}" {{ request('cor_id') == $cor->id ? 'selected' : '' }}>
                                    {{ $cor->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="tamanho_id" class="form-label">Tamanho</label>
                        <select name="tamanho_id" id="tamanho_id" class="form-select">
                            <option value="">Todos</option>
                             @foreach($tamanhos as $tamanho)
                                <option value="{{ $tamanho->id }}" {{ request('tamanho_id') == $tamanho->id ? 'selected' : '' }}>
                                    {{ $tamanho->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <div class="btn-group w-100">
                            <button type="submit" class="btn btn-primary" title="Aplicar Filtros">
                                <i class="bi bi-search"></i>
                            </button>
                            <a href="{{ route('produtos.index') }}" class="btn btn-secondary" title="Limpar Filtros">
                                <i class="bi bi-x-lg"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Status</th>
                            <th>Produto</th>
                            <th>Categorias</th>
                            <th class="text-center">Estoque</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($produtos as $produto)
                            <tr>
                                <td>
                                    @if($produto->ativo)
                                        <span class="badge bg-success-subtle border border-success-subtle text-success-emphasis rounded-pill"><i class="bi bi-check-circle-fill"></i> Ativo</span>
                                    @else
                                        <span class="badge bg-secondary-subtle border border-secondary-subtle text-secondary-emphasis rounded-pill"><i class="bi bi-x-circle-fill"></i> Inativo</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $produto->nome }}</div>
                                    <div class="small text-muted font-monospace">{{ $produto->sku ?? 'N/A' }}</div>
                                </td>
                                <td>
                                    @foreach($produto->categorias as $cat)
                                        <span class="badge bg-light text-dark border">{{ $cat->titulo }}</span>
                                    @endforeach
                                </td>
                                <td class="text-center fw-bold">{{ $produto->qtd_estoque ?? 0 }}</td>
                                <td class="text-end">
                                    <div class="btn-group">
                                        <a href="{{ route('produtos.show', $produto) }}" class="btn btn-sm btn-outline-secondary" title="Ver Detalhes"><i class="bi bi-eye"></i></a>
                                        <a href="{{ route('produtos.edit', $produto) }}" class="btn btn-sm btn-outline-primary" title="Editar"><i class="bi bi-pencil-square"></i></a>
                                        <form action="{{ route('produtos.destroy', $produto) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este produto e todas as suas variações?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Excluir"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Nenhum produto encontrado com os filtros aplicados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($produtos->hasPages())
            <div class="mt-3">
                {{ $produtos->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<div class="modal fade" id="modalAddProduto" tabindex="-1" aria-labelledby="modalAddProdutoLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form action="{{ route('produtos.store') }}" method="POST">
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalAddProdutoLabel">Adicionar Novo Produto</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
          </div>
          <div class="modal-body">
            <div class="row">
                <div class="col-md-8 mb-3">
                    <label for="add_nome" class="form-label">Nome *</label>
                    <input type="text" name="nome" id="add_nome" class="form-control" value="{{ old('nome') }}" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="add_sku" class="form-label">SKU</label>
                    <input type="text" name="sku" id="add_sku" class="form-control" value="{{ old('sku') }}">
                </div>
            </div>

            <div class="mb-3">
                <label for="add_descricao" class="form-label">Descrição</label>
                <textarea name="descricao" id="add_descricao" class="form-control" rows="3">{{ old('descricao') }}</textarea>
            </div>

            <div class="row align-items-end">
                 <div class="col-md-9 mb-3">
                    <label for="add_categorias" class="form-label">Categorias *</label>
                    <select name="categorias[]" id="add_categorias" class="form-select" multiple required>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}">{{ $categoria->titulo }}</option>
                        @endforeach
                    </select>
                    <small class="form-text text-muted">Segure Ctrl (ou Cmd no Mac) para múltiplas categorias.</small>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="add_peso" class="form-label">Peso (kg)</label>
                    <input type="number" step="0.001" name="peso" id="add_peso" class="form-control" value="{{ old('peso') }}" min="0">
                </div>
            </div>

             <div class="form-check form-switch fs-5 mt-2">
                <input type="hidden" name="ativo" value="0">
                <input class="form-check-input" type="checkbox" id="add_ativo" name="ativo" value="1" checked>
                <label class="form-check-label" for="add_ativo">Manter produto como Ativo</label>
            </div>

          </div>
          <div class="modal-footer">
             <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Salvar e Criar Produto</button>
          </div>
        </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('search_term');
    const resultsContainer = document.getElementById('search_results');

    searchInput.addEventListener('keyup', function() {
        const term = this.value;

        if (term.length < 2) {
            resultsContainer.innerHTML = '';
            resultsContainer.classList.remove('border');
            return;
        }

        fetch(`{{ route('produtos.search') }}?term=${term}`)
            .then(response => response.json())
            .then(data => {
                resultsContainer.innerHTML = '';
                resultsContainer.classList.add('border', 'rounded');

                if (data.length > 0) {
                    data.forEach(produto => {
                        const link = document.createElement('a');
                        link.href = produto.url;
                        link.className = 'list-group-item list-group-item-action';
                        link.textContent = produto.label;
                        resultsContainer.appendChild(link);
                    });
                } else {
                    const item = document.createElement('div');
                    item.className = 'list-group-item disabled text-muted';
                    item.textContent = 'Nenhum produto encontrado';
                    resultsContainer.appendChild(item);
                }
            })
            .catch(error => console.error('Erro na busca:', error));
    });

    document.addEventListener('click', function(event) {
        if (!searchInput.contains(event.target)) {
            resultsContainer.innerHTML = '';
            resultsContainer.classList.remove('border', 'rounded');
        }
    });
});
</script>
@endpush

@extends('Layouts.app')

@section('content')
<div class="container py-4">

    {{-- CABEÇALHO E MENSAGENS --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Gerenciar Cores</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <h5 class="alert-heading">Ocorreu um erro:</h5>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- CARD DE GERENCIAMENTO DE CORES --}}
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Cores Cadastradas</h5>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addColorModal">
                <i class="bi bi-plus-circle me-1"></i> Adicionar Cor
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nome da Cor</th>
                            <th class="text-center">Nº de Variações</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cores as $cor)
                            <tr>
                                <td class="fw-medium">{{ $cor->nome }}</td>
                                <td class="text-center">{{ $cor->variacoes_count ?? 0 }}</td>
                                <td class="text-end">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editColorModal"
                                            data-cor-nome="{{ $cor->nome }}"
                                            data-update-url="{{ route('cores.update', $cor) }}">
                                            <i class="bi bi-pencil-square"></i> Editar
                                        </button>
                                        <form action="{{ route('cores.destroy', $cor->id) }}" method="POST" onsubmit="return confirm('Tem certeza?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i> Excluir
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">Nenhuma cor cadastrada.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addColorModal" tabindex="-1" aria-labelledby="addColorModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('cores.store') }}" method="POST">
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addColorModalLabel">Adicionar Nova Cor</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
          </div>
          <div class="modal-body">
              <div class="mb-3">
                  <label for="nome" class="form-label">Nome da Cor *</label>
                  <input type="text" name="nome" id="nome" class="form-control" placeholder="Ex: Vermelho" required>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Salvar</button>
          </div>
        </div>
    </form>
  </div>
</div>

<div class="modal fade" id="editColorModal" tabindex="-1" aria-labelledby="editColorModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="editColorForm" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editColorModalLabel">Editar Cor</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
          </div>
          <div class="modal-body">
              <div class="mb-3">
                  <label for="edit_nome" class="form-label">Nome da Cor *</label>
                  <input type="text" name="nome" id="edit_nome" class="form-control" required>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
          </div>
        </div>
    </form>
  </div>
</div>
@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const editModal = document.getElementById('editColorModal');
    if (editModal) {
        editModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            // Extrai as informações dos atributos data-*
            const corNome = button.getAttribute('data-cor-nome');
            const updateUrl = button.getAttribute('data-update-url');

            // Atualiza os elementos do modal
            const modalForm = editModal.querySelector('#editColorForm');
            const modalInputNome = editModal.querySelector('#edit_nome');

            modalForm.action = updateUrl;
            modalInputNome.value = corNome;
        });
    }
});
</script>
@endpush

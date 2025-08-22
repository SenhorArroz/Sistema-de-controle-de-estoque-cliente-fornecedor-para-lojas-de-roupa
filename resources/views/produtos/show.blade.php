@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 mb-0">Gerenciar Produto: <span class="fw-normal">{{ $produto->nome }}</span></h1>
        <a href="{{ route('produtos.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Voltar
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger" role="alert">
            <h5 class="alert-heading">Ocorreram erros de validação:</h5>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <div class="card shadow-sm mb-4">
        <div class="card-header"><h5 class="mb-0">Detalhes Principais do Produto</h5></div>
        <div class="card-body">
            <form action="{{ route('produtos.update', $produto) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-8 mb-3"><label for="nome" class="form-label">Nome *</label><input type="text" name="nome" id="nome" value="{{ old('nome', $produto->nome) }}" class="form-control" required></div>
                    <div class="col-md-8 mb-3"><label for="fornecedor_id" class="form-label">Fornecedor *</label>
                        <select name="fornecedor_id" id="fornecedor_id" class="form-select" required>
                            <option value="">Selecione o fornecedor</option>
                            @foreach(\App\Models\Fornecedor::all() as $fornecedor)
                                <option value="{{ $fornecedor->id }}" {{ old('fornecedor_id', $produto->fornecedor_id) == $fornecedor->id ? 'selected' : '' }}>
                                    {{ $fornecedor->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mb-3"><label for="descricao" class="form-label">Descrição</label><textarea name="descricao" id="descricao" class="form-control" rows="3">{{ old('descricao', $produto->descricao) }}</textarea></div>
                <div class="row align-items-center">
                    <div class="col-md-8 mb-3"><label for="categorias" class="form-label">Categorias *</label><select name="categorias[]" id="categorias" class="form-select" multiple required>@foreach($categorias as $categoria)<option value="{{ $categoria->id }}" @if($produto->categorias->contains($categoria->id)) selected @endif>{{ $categoria->titulo }}</option>@endforeach</select><small class="form-text fw-bold text-muted">Segure Ctrl (ou Cmd no Mac) para selecionar.</small></div>
                    <div class="col-md-2 mb-3"><label for="peso" class="form-label">Peso (kg)</label><input type="number" step="0.001" name="peso" id="peso" value="{{ old('peso', $produto->peso) }}" class="form-control" min="0"></div>
                    <div class="col-md-2 mb-3"><div class="form-check form-switch fs-5"><input type="hidden" name="ativo" value="0"><input class="form-check-input" type="checkbox" id="ativo" name="ativo" value="1" @if($produto->ativo) checked @endif><label class="form-check-label" for="ativo">Ativo</label></div></div>
                </div>
                <div class="text-end"><button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Atualizar Produto</button></div>
            </form>
        </div>
    </div>


    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Variações do Produto</h5>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addVariationModal">
                <i class="bi bi-plus-circle me-1"></i> Adicionar Nova Variação
            </button>
        </div>
        <div class="card-body">
            @php
                $coresUnicas = $produto->variacoes->pluck('cor')->unique('id')->sortBy('nome');
                $tamanhosUnicos = $produto->variacoes->pluck('tamanho')->unique('id')->sortBy('nome');
                $categoriasUnicas = $produto->variacoes->pluck('categorias')->flatten()->unique('id')->sortBy('titulo');
            @endphp

            <div class="filtros-variacoes bg-light border rounded p-3 mb-4">
                <div class="row g-3 align-items-center">
                    <div class="col-md-3">
                        <label for="filtro_cor" class="form-label">Filtrar por Cor</label>
                        <select id="filtro_cor" class="form-select form-select-sm">
                            <option value="">Todas as Cores</option>
                            @foreach($coresUnicas as $cor)
                                <option value="{{ $cor->id }}">{{ $cor->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="filtro_tamanho" class="form-label">Filtrar por Tamanho</label>
                        <select id="filtro_tamanho" class="form-select form-select-sm">
                            <option value="">Todos os Tamanhos</option>
                             @foreach($tamanhosUnicos as $tamanho)
                                <option value="{{ $tamanho->id }}">{{ $tamanho->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="filtro_categoria" class="form-label">Filtrar por Categoria</label>
                        <select id="filtro_categoria" class="form-select form-select-sm">
                            <option value="">Todas as Categorias</option>
                            @foreach($categoriasUnicas as $categoria)
                                <option value="{{ $categoria->id }}">{{ $categoria->titulo }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button id="limpar_filtros" class="btn btn-secondary btn-sm w-100">Limpar</button>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-bordered table-striped align-middle" id="tabela-variacoes">
                    <thead class="table-light text-center">
                        <tr>
                            <th>Ativo</th>
                            <th>Variação</th>
                            <th>Estoque</th>
                            <th>Códigos de Barra</th>
                            <th>Valor de Venda</th>
                            <th style="width: 180px;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($produto->variacoes as $variacao)
                            <tr class="linha-variacao @if($variacao->ativo && $variacao->quantidade <= $variacao->estoque_minimo) table-warning @endif"
                                data-cor-id="{{ $variacao->cor_id }}"
                                data-tamanho-id="{{ $variacao->tamanho_id }}"
                                data-categoria-ids="{{ json_encode($variacao->categorias->pluck('id')) }}">
                                <td class="text-center">@if($variacao->ativo)<span class="badge bg-success">Sim</span>@else<span class="badge bg-secondary">Não</span>@endif</td>
                                <td>{{ $variacao->cor->nome }} / {{ $variacao->tamanho->nome }}</td>
                                <td class="text-center"><span class="fw-bold">{{ $variacao->quantidade }}</span></td>
                                <td style="max-width: 200px;">
                                    @if($variacao->codigosBarras->isNotEmpty())
                                        <div class="d-flex flex-column" style="max-height: 75px; overflow-y: auto;">
                                            @foreach($variacao->codigosBarras as $codigo)
                                                <span class="badge bg-light text-dark font-monospace text-start mb-1"><i class="bi fw-bold bi-upc"></i> {{ $codigo->codigo_barra }}</span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-muted small">Nenhum</span>
                                    @endif
                                </td>
                                <td class="text-center"><span class="fw-bold">R$: {{ $variacao->valor_venda }}</span></td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editVariationModal" data-variation='{{ $variacao->load('codigosBarras') }}'>
                                            <i class="bi bi-pencil-square"></i> Gerenciar
                                        </button>
                                        <form action="{{ route('variacoes.destroy', $variacao->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir esta variação?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr id="linha-sem-variacoes">
                                <td colspan="6" class="text-center text-muted py-3">Nenhuma variação cadastrada.</td>
                            </tr>
                        @endforelse
                         <tr id="linha-nenhum-resultado" class="d-none">
                            <td colspan="6" class="text-center text-danger py-3">Nenhum resultado encontrado para os filtros aplicados.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="addVariationModal" tabindex="-1" aria-labelledby="addVariationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('variacoes.store') }}" method="POST">
                @csrf
                <input type="hidden" name="produto_id" value="{{ $produto->id }}">
                <div class="modal-header"><h5 class="modal-title" id="addVariationModalLabel">Adicionar Nova Variação</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                <div class="modal-body">
                    <div class="row">
                       <div class="col-md-6 mb-3"><label for="add_cor_id" class="form-label">Cor *</label><select name="cor_id" id="add_cor_id" class="form-select" required><option value="" disabled selected>Selecione...</option>@foreach(\App\Models\Cor::all() as $cor)<option value="{{ $cor->id }}">{{ $cor->nome }}</option>@endforeach</select></div>
                       <div class="col-md-6 mb-3"><label for="add_tamanho_id" class="form-label">Tamanho *</label><select name="tamanho_id" id="add_tamanho_id" class="form-select" required><option value="" disabled selected>Selecione...</option>@foreach(\App\Models\Tamanho::all() as $tamanho)<option value="{{ $tamanho->id }}">{{ $tamanho->nome }}</option>@endforeach</select></div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-3"><label for="add_ativo" class="form-label">Ativo *</label><select name="ativo" id="add_ativo" class="form-select" required><option value="1" selected>Sim</option><option value="0">Não</option></select></div>
                    </div>
                    <div class="row">
                       <div class="col-md-3 mb-3"><label for="add_valor_compra" class="form-label">Valor Compra *</label><input type="number" step="0.01" name="valor_compra" id="add_valor_compra" class="form-control" required></div>
                       <div class="col-md-3 mb-3"><label for="add_valor_venda" class="form-label">Valor Venda *</label><input type="number" step="0.01" name="valor_venda" id="add_valor_venda" class="form-control" required></div>
                       <div class="col-md-3 mb-3"><label for="add_quantidade" class="form-label">Quantidade *</label><input type="number" name="quantidade" id="add_quantidade" class="form-control" min="0" required></div>
                       <div class="col-md-3 mb-3"><label for="add_estoque_minimo" class="form-label">Estoque Mínimo</label><input type="number" name="estoque_minimo" id="add_estoque_minimo" class="form-control" min="0"></div>
                    </div>
                    <div class="mb-3"><label for="add_categorias" class="form-label">Categorias da Variação *</label><select name="categorias[]" id="add_categorias" class="form-select" multiple required>@foreach($categorias as $categoria) <option value="{{ $categoria->id }}">{{ $categoria->titulo }}</option> @endforeach</select></div>
                    <div class="mb-3">
                        <label for="codigos_barras" class="form-label">Códigos de Barras (um por linha)</label>
                        <div class="input-group">
                            <textarea name="codigos_barras" id="codigos_barras" class="form-control" rows="3" placeholder="789000000001&#10;789000000002"></textarea>
                            <button class="btn btn-outline-secondary" type="button" id="generateBarcodeForAdd">Gerar</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button><button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Salvar Variação</button></div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="editVariationModal" tabindex="-1" aria-labelledby="editVariationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title" id="editVariationModalLabel">Gerenciar Variação</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-7 border-end">
                        <h6>Detalhes da Variação</h6>
                        <form id="editVariationForm" action="{{ route('variacoes.update', $variacao->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                               <div class="col-md-6 mb-3"><label for="edit_cor_id" class="form-label">Cor *</label><select name="cor_id" id="edit_cor_id" class="form-select" required>@foreach(\App\Models\Cor::all() as $cor)<option value="{{ $cor->id }}">{{ $cor->nome }}</option>@endforeach</select></div>
                               <div class="col-md-6 mb-3"><label for="edit_tamanho_id" class="form-label">Tamanho *</label><select name="tamanho_id" id="edit_tamanho_id" class="form-select" required>@foreach(\App\Models\Tamanho::all() as $tamanho)<option value="{{ $tamanho->id }}">{{ $tamanho->nome }}</option>@endforeach</select></div>
                            </div>
                            <div class="row">
                               <div class="col-md-6 mb-3"><label for="edit_valor_compra" class="form-label">Valor Compra *</label><input type="number" step="0.01" name="valor_compra" id="edit_valor_compra" class="form-control" required></div>
                               <div class="col-md-6 mb-3"><label for="edit_valor_venda" class="form-label">Valor Venda *</label><input type="number" step="0.01" name="valor_venda" id="edit_valor_venda" class="form-control" required></div>
                            </div>
                            <div class="row">
                               <div class="col-md-4 mb-3"><label for="edit_quantidade" class="form-label">Quantidade *</label><input type="number" name="quantidade" id="edit_quantidade" class="form-control" min="0" required></div>
                               <div class="col-md-4 mb-3"><label for="edit_estoque_minimo" class="form-label">Estoque Mínimo *</label><input type="number" name="estoque_minimo" id="edit_estoque_minimo" class="form-control" min="0" required></div>
                               <div class="col-md-4 mb-3"><label for="edit_ativo" class="form-label">Ativo *</label><select name="ativo" id="edit_ativo" class="form-select" required><option value="1">Sim</option><option value="0">Não</option></select></div>
                            </div>
                            <div class="text-end"><button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Salvar Detalhes</button></div>
                        </form>
                    </div>
                    <div class="col-md-5">
                        <h6>Códigos de Barra</h6>
                        <div id="barcodeList" class="list-group mb-3" style="max-height: 200px; overflow-y: auto;"></div>
                        <h6>Adicionar Novos Códigos</h6>
                        <form id="addBarcodesForm" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="novos_codigos" class="form-label">Novos Códigos (um por linha)</label>
                                <div class="input-group">
                                    <textarea name="novos_codigos" id="novos_codigos" class="form-control" rows="3" required></textarea>
                                    <button class="btn btn-outline-secondary" type="button" id="generateBarcodeForEdit">Gerar</button>
                                </div>
                            </div>
                            <div class="text-end"><button type="submit" class="btn btn-success"><i class="bi bi-plus-circle"></i> Adicionar Códigos</button></div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button></div>
        </div>
    </div>
</div>
@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const editModal = document.getElementById('editVariationModal');
    if (editModal) {
        editModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const variation = JSON.parse(button.getAttribute('data-variation'));
            const editForm = editModal.querySelector('#editVariationForm');
            editForm.action = `/variacoes/${variation.id}`;
            editModal.querySelector('#edit_cor_id').value = variation.cor_id;
            editModal.querySelector('#edit_tamanho_id').value = variation.tamanho_id;
            editModal.querySelector('#edit_valor_compra').value = variation.valor_compra;
            editModal.querySelector('#edit_valor_venda').value = variation.valor_venda;
            editModal.querySelector('#edit_quantidade').value = variation.quantidade;
            editModal.querySelector('#edit_estoque_minimo').value = variation.estoque_minimo;
            editModal.querySelector('#edit_ativo').value = variation.ativo;
            const addBarcodesForm = editModal.querySelector('#addBarcodesForm');
            addBarcodesForm.action = `/variacoes/${variation.id}/codigos`;
            const barcodeList = editModal.querySelector('#barcodeList');
            barcodeList.innerHTML = '';
            if (variation.codigos_barras && variation.codigos_barras.length > 0) {
                variation.codigos_barras.forEach(codigo => {
                    const item = document.createElement('div');
                    item.className = 'list-group-item d-flex justify-content-between align-items-center py-1';
                    item.innerHTML = `<span class="font-monospace">${codigo.codigo_barra}</span>
                        <form action="/variacoes/${variation.id}/codigos/${codigo.id}" method="POST" onsubmit="return confirm('Excluir este código?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-1"><i class="bi bi-x-lg"></i></button>
                        </form>`;
                    barcodeList.appendChild(item);
                });
            } else {
                barcodeList.innerHTML = '<p class="text-muted small p-2">Nenhum código de barra cadastrado.</p>';
            }
        });
    }

    function generateEan13() {
        let code = '200';
        for (let i = 0; i < 9; i++) { code += Math.floor(Math.random() * 10); }
        let sum = 0;
        for (let i = 0; i < code.length; i++) { sum += parseInt(code[i]) * ((i % 2 === 0) ? 1 : 3); }
        const checkDigit = (10 - (sum % 10)) % 10;
        return code + checkDigit;
    }

    function appendToTextarea(textarea, text) {
        if (textarea.value.trim() === '') {
            textarea.value = text;
        } else {
            textarea.value += '\n' + text;
        }
        textarea.scrollTop = textarea.scrollHeight;
    }

    const btnGenAdd = document.getElementById('generateBarcodeForAdd');
    const btnGenEdit = document.getElementById('generateBarcodeForEdit');

    if(btnGenAdd) {
        btnGenAdd.addEventListener('click', function() {
            const textarea = document.getElementById('codigos_barras');
            appendToTextarea(textarea, generateEan13());
        });
    }

    if(btnGenEdit) {
        btnGenEdit.addEventListener('click', function() {
            const textarea = document.getElementById('novos_codigos');
            appendToTextarea(textarea, generateEan13());
        });
    }

    const filtroCor = document.getElementById('filtro_cor');
    const filtroTamanho = document.getElementById('filtro_tamanho');
    const filtroCategoria = document.getElementById('filtro_categoria');
    const btnLimpar = document.getElementById('limpar_filtros');
    const tabelaVariacoes = document.getElementById('tabela-variacoes');
    const linhas = tabelaVariacoes.querySelectorAll('tbody tr.linha-variacao');
    const linhaNenhumResultado = document.getElementById('linha-nenhum-resultado');

    function aplicarFiltros() {
        const corSelecionada = filtroCor.value;
        const tamanhoSelecionado = filtroTamanho.value;
        const categoriaSelecionada = filtroCategoria.value;
        let resultadosVisiveis = 0;

        linhas.forEach(linha => {
            const corId = linha.dataset.corId;
            const tamanhoId = linha.dataset.tamanhoId;
            const categoriaIds = JSON.parse(linha.dataset.categoriaIds);

            let mostrar = true;

            if (corSelecionada && corId !== corSelecionada) { mostrar = false; }
            if (tamanhoSelecionado && tamanhoId !== tamanhoSelecionado) { mostrar = false; }
            if (categoriaSelecionada && !categoriaIds.includes(parseInt(categoriaSelecionada))) { mostrar = false; }

            linha.style.display = mostrar ? '' : 'none';
            if(mostrar) { resultadosVisiveis++; }
        });

        linhaNenhumResultado.classList.toggle('d-none', resultadosVisiveis > 0 || linhas.length === 0);
    }

    if (filtroCor) {
        filtroCor.addEventListener('change', aplicarFiltros);
        filtroTamanho.addEventListener('change', aplicarFiltros);
        filtroCategoria.addEventListener('change', aplicarFiltros);
        btnLimpar.addEventListener('click', () => {
            filtroCor.value = '';
            filtroTamanho.value = '';
            filtroCategoria.value = '';
            aplicarFiltros();
        });
    }
});
</script>
@endpush

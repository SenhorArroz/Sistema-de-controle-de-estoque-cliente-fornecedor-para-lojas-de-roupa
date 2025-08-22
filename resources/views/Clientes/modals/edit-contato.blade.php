<div class="modal fade" id="editContatoModal-{{ $contato->id }}" tabindex="-1" aria-labelledby="editContatoModalLabel-{{ $contato->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('clientes.contatos.update', ['cliente' => $cliente->id, 'contato' => $contato->id]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editContatoModalLabel-{{ $contato->id }}">Editar Contato</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tipo</label>
                        <select class="form-select" name="tipo" required>
                            <option value="telefone" @if($contato->tipo == 'telefone') selected @endif>Telefone</option>
                            <option value="email" @if($contato->tipo == 'email') selected @endif>Email</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Título</label>
                        <input type="text" class="form-control" name="titulo" value="{{ $contato->titulo }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contato</label>
                        <input type="text" class="form-control" name="contato" value="{{ $contato->contato }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteContatoModal-{{ $contato->id }}" tabindex="-1" aria-labelledby="deleteContatoModalLabel-{{ $contato->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('clientes.contatos.destroy', ['cliente' => $cliente->id, 'contato' => $contato->id]) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteContatoModalLabel-{{ $contato->id }}">Confirmar Exclusão</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Tem a certeza que deseja excluir este contato?</p>
                    <p class="text-muted">{{ $contato->titulo }}: {{ $contato->contato }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Excluir Contato</button>
                </div>
            </form>
        </div>
    </div>
</div>

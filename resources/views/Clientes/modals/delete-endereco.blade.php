<div class="modal fade" id="deleteEnderecoModal-{{ $endereco->id }}" tabindex="-1" aria-labelledby="deleteEnderecoModalLabel-{{ $endereco->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('clientes.enderecos.destroy', ['cliente' => $cliente->id, 'endereco' => $endereco->id]) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteEnderecoModalLabel-{{ $endereco->id }}">Confirmar Exclusão</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Tem a certeza que deseja excluir este endereço?</p>
                    <p class="text-muted">{{ $endereco->rua }}, {{ $endereco->numero }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Excluir Endereço</button>
                </div>
            </form>
        </div>
    </div>
</div>

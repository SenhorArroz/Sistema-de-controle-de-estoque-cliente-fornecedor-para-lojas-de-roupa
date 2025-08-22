<div class="modal fade" id="addContatoModal" tabindex="-1" aria-labelledby="addContatoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('clientes.contatos.store', $cliente->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addContatoModalLabel">Adicionar Novo Contato</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tipo</label>
                        <select class="form-select" name="tipo" required>
                            <option value="telefone">Telefone</option>
                            <option value="email">Email</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Título</label>
                        <input type="text" class="form-control" name="titulo" placeholder="Ex: Celular Pessoal" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contato</label>
                        <input type="text" class="form-control" name="contato" placeholder="(99) 99999-9999 ou email@exemplo.com" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar Contato</button>
                </div>
            </form>
        </div>
    </div>
</div>

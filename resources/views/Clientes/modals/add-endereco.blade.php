<div class="modal fade" id="addEnderecoModal" tabindex="-1" aria-labelledby="addEnderecoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('clientes.enderecos.store', $cliente->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addEnderecoModalLabel">Adicionar Novo Endereço</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Rua</label>
                            <input type="text" class="form-control" name="rua" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Número</label>
                            <input type="text" class="form-control" name="numero" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Bairro</label>
                            <input type="text" class="form-control" name="bairro" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Complemento</label>
                            <input type="text" class="form-control" name="complemento">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Cidade</label>
                            <input type="text" class="form-control" name="cidade" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Estado</label>
                            <input type="text" class="form-control" name="estado" maxlength="2" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">CEP</label>
                            <input type="text" class="form-control" name="cep" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar Endereço</button>
                </div>
            </form>
        </div>
    </div>
</div>

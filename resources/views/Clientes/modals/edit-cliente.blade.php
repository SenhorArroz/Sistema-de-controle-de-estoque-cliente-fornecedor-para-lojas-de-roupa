<div class="modal fade" id="editClientModal" tabindex="-1" aria-labelledby="editClientModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('clientes.update', $cliente->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editClientModalLabel">Editar Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="edit_nome_completo" class="form-label">Nome Completo</label>
                            <input type="text" class="form-control" id="edit_nome_completo" name="nome_completo" value="{{ old('nome_completo', $cliente->nome_completo) }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="edit_cpf_cnpj" class="form-label">CPF/CNPJ</label>
                            <input type="text" class="form-control" id="edit_cpf_cnpj" name="cpf_cnpj" value="{{ old('cpf_cnpj', $cliente->cpf_cnpj) }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_imagem_perfil" class="form-label">Alterar Foto do Perfil</label>
                        <input class="form-control" type="file" id="edit_imagem_perfil" name="imagem_perfil">
                        <small class="form-text text-muted">Deixe em branco para manter a foto atual.</small>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <label for="edit_observacao" class="form-label">Observações Gerais</label>
                        <textarea class="form-control" id="edit_observacao" name="observacao" rows="4">{{ old('observacao', $cliente->observacao) }}</textarea>
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

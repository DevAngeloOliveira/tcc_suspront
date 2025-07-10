<div>
    <div class="card">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Lista de Médicos</h5>
                <a href="{{ route('medicos.create') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-plus"></i> Novo Médico
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Buscar por nome, CRM ou especialidade"
                            wire:model.live.debounce.300ms="search">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-6">
                    <select class="form-select" wire:model.live="especialidadeFiltro">
                        <option value="">Todas as especialidades</option>
                        @foreach ($especialidades as $especialidade)
                            <option value="{{ $especialidade }}">{{ $especialidade }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="table-responsive" wire:loading.class="opacity-50">
                <table class="table table-hover table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nome</th>
                            <th>CRM</th>
                            <th>Especialidade</th>
                            <th>Email</th>
                            <th>Telefone</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($medicos as $medico)
                            <tr>
                                <td>{{ $medico->nome }}</td>
                                <td>{{ $medico->crm }}</td>
                                <td>{{ $medico->especialidade }}</td>
                                <td>{{ $medico->email }}</td>
                                <td>{{ $medico->telefone ?: 'Não informado' }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('medicos.show', $medico->id) }}" class="btn btn-info"
                                            title="Visualizar">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('medicos.edit', $medico->id) }}" class="btn btn-warning"
                                            title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger"
                                            wire:click="confirmarExclusao({{ $medico->id }})" title="Excluir">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="fas fa-user-md fa-3x text-muted mb-2"></i>
                                        <h5 class="text-muted">Nenhum médico encontrado</h5>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $medicos->links() }}
            </div>
        </div>
    </div>

    <!-- Modal de confirmação de exclusão -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirmar Exclusão</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Tem certeza que deseja excluir este médico? Esta ação não pode ser desfeita.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">
                        <i class="fas fa-trash"></i> Excluir
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('livewire:initialized', () => {
                let medicoIdToDelete = null;
                const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

                // Abrir modal de confirmação
                @this.on('openDeleteModal', (data) => {
                    medicoIdToDelete = data.medicoId;
                    deleteModal.show();
                });

                // Confirmar exclusão
                document.getElementById('confirmDelete').addEventListener('click', function() {
                    if (medicoIdToDelete) {
                        @this.deletarMedico(medicoIdToDelete);
                        deleteModal.hide();
                    }
                });
            });
        </script>
    @endpush
</div>

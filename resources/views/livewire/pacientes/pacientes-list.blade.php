<div class="container-fluid">
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <h2>Lista de Pacientes</h2>
        <a href="{{ route('pacientes.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Novo Paciente
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" wire:model.live.debounce.300ms="search" class="form-control"
                            placeholder="Buscar por nome, CPF ou cartão SUS" autofocus>
                        <button class="btn btn-outline-success" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">
            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive" wire:loading.class.delay="opacity-50">
                @if ($pacientes->count() > 0)
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>CPF</th>
                                <th>Cartão SUS</th>
                                <th>Data Nascimento</th>
                                <th>Telefone</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pacientes as $paciente)
                                <tr>
                                    <td>{{ $paciente->nome }}</td>
                                    <td>{{ $paciente->cpf }}</td>
                                    <td>{{ $paciente->cartao_sus }}</td>
                                    <td>{{ \Carbon\Carbon::parse($paciente->data_nascimento)->format('d/m/Y') }}</td>
                                    <td>{{ $paciente->telefone ?: 'Não informado' }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('pacientes.show', $paciente->id) }}"
                                                class="btn btn-sm btn-info" title="Visualizar">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('pacientes.edit', $paciente->id) }}"
                                                class="btn btn-sm btn-warning" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger"
                                                wire:click="$dispatch('openDeleteModal', { pacienteId: {{ $paciente->id }} })"
                                                title="Excluir">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-4 d-flex justify-content-center">
                        {{ $pacientes->links() }}
                    </div>
                @else
                    <p class="text-center">Nenhum paciente encontrado.</p>
                @endif
            </div>

            <div wire:loading class="text-center py-3">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Carregando...</span>
                </div>
                <p class="mt-2">Carregando dados...</p>
            </div>
        </div>
    </div>

    <!-- Modal de confirmação para excluir -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirmar exclusão</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="deleteModalBody">
                    <p>Tem certeza que deseja excluir este paciente?</p>
                    <p class="text-danger"><small>Esta ação não pode ser desfeita e todos os registros relacionados
                            serão
                            excluídos.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Excluir</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            let pacienteIdToDelete = null;

            // Livewire 3 syntax
            document.addEventListener('livewire:dispatch', event => {
                if (event.detail.name === 'openDeleteModal') {
                    pacienteIdToDelete = event.detail.data.pacienteId;
                    deleteModal.show();
                }
            });

            document.getElementById('confirmDeleteBtn').addEventListener('click', () => {
                if (pacienteIdToDelete) {
                    @this.excluirPaciente(pacienteIdToDelete);
                    deleteModal.hide();
                }
            });

            document.getElementById('deleteModal').addEventListener('hidden.bs.modal', () => {
                pacienteIdToDelete = null;
            });
        });
    </script>
@endpush

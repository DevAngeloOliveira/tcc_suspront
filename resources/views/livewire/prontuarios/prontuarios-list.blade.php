<div>
    <div class="card">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Lista de Prontuários</h5>
                <a href="{{ route('pacientes.index') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-user-injured"></i> Ver Pacientes
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" class="form-control"
                            placeholder="Buscar por nome do paciente ou cartão SUS"
                            wire:model.live.debounce.300ms="search">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="table-responsive" wire:loading.class="opacity-50">
                <table class="table table-hover table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Paciente</th>
                            <th>Cartão SUS</th>
                            <th>Data de Nascimento</th>
                            <th>Consultas</th>
                            <th>Última Atualização</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($prontuarios as $prontuario)
                            <tr>
                                <td>{{ $prontuario->paciente->nome }}</td>
                                <td>{{ $prontuario->paciente->cartao_sus }}</td>
                                <td>{{ \Carbon\Carbon::parse($prontuario->paciente->data_nascimento)->format('d/m/Y') }}
                                </td>
                                <td>{{ $prontuario->consultas_count ?? $prontuario->consultas->count() }}</td>
                                <td>{{ $prontuario->updated_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('prontuarios.show', $prontuario->id) }}" class="btn btn-info"
                                            title="Visualizar Prontuário">
                                            <i class="fas fa-folder-open"></i>
                                        </a>
                                        <a href="{{ route('pacientes.show', $prontuario->paciente->id) }}"
                                            class="btn btn-secondary" title="Perfil do Paciente">
                                            <i class="fas fa-user"></i>
                                        </a>
                                        <a href="{{ route('consultas.create', ['paciente_id' => $prontuario->paciente->id]) }}"
                                            class="btn btn-success" title="Nova Consulta">
                                            <i class="fas fa-calendar-plus"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="fas fa-file-medical-alt fa-3x text-muted mb-2"></i>
                                        <h5 class="text-muted">Nenhum prontuário encontrado</h5>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $prontuarios->links() }}
            </div>
        </div>
    </div>
</div>

<div>
    <div class="card">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Lista de Consultas</h5>
                <a href="{{ route('consultas.create') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-plus"></i> Nova Consulta
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-3">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Buscar paciente ou médico"
                            wire:model.live.debounce.300ms="search">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select" wire:model.live="statusFiltro">
                        <option value="">Todos os status</option>
                        <option value="agendada">Agendada</option>
                        <option value="confirmada">Confirmada</option>
                        <option value="em_atendimento">Em Atendimento</option>
                        <option value="concluida">Concluída</option>
                        <option value="cancelada">Cancelada</option>
                        <option value="remarcada">Remarcada</option>
                    </select>
                </div>
                @if (auth()->user()->tipo !== 'medico')
                    <div class="col-md-3">
                        <select class="form-select" wire:model.live="medicoFiltro">
                            <option value="">Todos os médicos</option>
                            @foreach ($medicos as $medico)
                                <option value="{{ $medico->id }}">{{ $medico->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="col-md-3">
                    <input type="date" class="form-control" wire:model.live="dataFiltro">
                </div>
            </div>

            <div class="table-responsive" wire:loading.class="opacity-50">
                <table class="table table-hover table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Horário</th>
                            <th>Paciente</th>
                            <th>Médico</th>
                            <th>Tipo</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($consultas as $consulta)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($consulta->data_hora)->format('d/m/Y H:i') }}</td>
                                <td>{{ $consulta->paciente->nome }}</td>
                                <td>{{ $consulta->medico->nome }}</td>
                                <td>{{ ucfirst($consulta->tipo) }}</td>
                                <td>
                                    <span
                                        class="badge bg-{{ $consulta->status == 'agendada'
                                            ? 'primary'
                                            : ($consulta->status == 'confirmada'
                                                ? 'info'
                                                : ($consulta->status == 'em_atendimento'
                                                    ? 'warning'
                                                    : ($consulta->status == 'concluida'
                                                        ? 'success'
                                                        : ($consulta->status == 'remarcada'
                                                            ? 'secondary'
                                                            : 'danger')))) }}">
                                        {{ ucfirst(str_replace('_', ' ', $consulta->status)) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('consultas.show', $consulta->id) }}" class="btn btn-info"
                                            title="Visualizar">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        @if (in_array($consulta->status, ['agendada', 'confirmada']))
                                            <a href="{{ route('consultas.edit', $consulta->id) }}"
                                                class="btn btn-warning" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            @if (auth()->user()->tipo == 'atendente' || auth()->user()->tipo == 'admin')
                                                <button type="button" class="btn btn-primary"
                                                    wire:click="updateStatus({{ $consulta->id }}, 'confirmada')"
                                                    @if ($consulta->status == 'confirmada') disabled @endif title="Confirmar">
                                                    <i class="fas fa-check"></i>
                                                </button>

                                                @if ($consulta->status != 'confirmada')
                                                    <a href="{{ route('consultas.remarcacao.edit', $consulta->id) }}"
                                                        class="btn btn-secondary" title="Remarcar">
                                                        <i class="fas fa-calendar-alt"></i>
                                                    </a>
                                                @endif
                                            @endif
                                        @endif

                                        @if (auth()->user()->tipo == 'medico' && $consulta->status == 'confirmada')
                                            <button type="button" class="btn btn-success"
                                                wire:click="updateStatus({{ $consulta->id }}, 'em_atendimento')"
                                                title="Iniciar Atendimento">
                                                <i class="fas fa-play"></i>
                                            </button>
                                        @endif

                                        @if (auth()->user()->tipo == 'medico' && $consulta->status == 'em_atendimento')
                                            <a href="{{ route('consultas.concluir', $consulta->id) }}"
                                                class="btn btn-success" title="Concluir Atendimento">
                                                <i class="fas fa-check-double"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="fas fa-calendar-times fa-3x text-muted mb-2"></i>
                                        <h5 class="text-muted">Nenhuma consulta encontrada</h5>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $consultas->links() }}
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            @this.on('alert', (data) => {
                const alertDiv = document.createElement('div');
                alertDiv.className = `alert alert-${data.type} alert-dismissible fade show`;
                alertDiv.innerHTML = `
                    ${data.message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;

                const cardBody = document.querySelector('.card-body');
                cardBody.insertBefore(alertDiv, cardBody.firstChild);

                // Auto-hide after 3 seconds
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alertDiv);
                    bsAlert.close();
                }, 3000);
            });
        });
    </script>
</div>

<div>
    <div class="card">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Lista de Exames</h5>
                <a href="{{ route('exames.create') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-plus"></i> Novo Exame
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-3">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Buscar exame ou paciente"
                            wire:model.live.debounce.300ms="search">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select" wire:model.live="statusFiltro">
                        <option value="">Todos os status</option>
                        <option value="solicitado">Solicitado</option>
                        <option value="agendado">Agendado</option>
                        <option value="realizado">Realizado</option>
                        <option value="cancelado">Cancelado</option>
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
                            <th>Paciente</th>
                            <th>Tipo de Exame</th>
                            <th>Médico Solicitante</th>
                            <th>Data Solicitação</th>
                            <th>Data Agendada</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($exames as $exame)
                            <tr>
                                <td>{{ $exame->paciente->nome }}</td>
                                <td>{{ $exame->tipo_exame }}</td>
                                <td>{{ $exame->medico->nome }}</td>
                                <td>{{ \Carbon\Carbon::parse($exame->data_solicitacao)->format('d/m/Y') }}</td>
                                <td>
                                    @if ($exame->data_agendada)
                                        {{ \Carbon\Carbon::parse($exame->data_agendada)->format('d/m/Y H:i') }}
                                    @else
                                        <span class="text-muted">Não agendado</span>
                                    @endif
                                </td>
                                <td>
                                    <span
                                        class="badge bg-{{ $exame->status == 'solicitado'
                                            ? 'warning'
                                            : ($exame->status == 'agendado'
                                                ? 'info'
                                                : ($exame->status == 'realizado'
                                                    ? 'success'
                                                    : 'danger')) }}">
                                        {{ ucfirst($exame->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('exames.show', $exame->id) }}" class="btn btn-info"
                                            title="Visualizar">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        @if ($exame->status == 'solicitado')
                                            <a href="{{ route('exames.edit', $exame->id) }}" class="btn btn-warning"
                                                title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if (auth()->user()->tipo == 'atendente' || auth()->user()->tipo == 'admin')
                                                <a href="{{ route('exames.agendar', $exame->id) }}"
                                                    class="btn btn-primary" title="Agendar">
                                                    <i class="fas fa-calendar-plus"></i>
                                                </a>
                                            @endif
                                        @endif

                                        @if ($exame->status == 'realizado' && $exame->resultado_url)
                                            <button type="button" class="btn btn-secondary"
                                                wire:click="visualizarResultado({{ $exame->id }})"
                                                title="Ver Resultado">
                                                <i class="fas fa-file-medical"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="fas fa-microscope fa-3x text-muted mb-2"></i>
                                        <h5 class="text-muted">Nenhum exame encontrado</h5>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $exames->links() }}
            </div>
        </div>
    </div>
</div>

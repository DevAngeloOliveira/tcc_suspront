<div>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Lista de Consultas</h2>
            <a href="{{ route('consultas.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Nova Consulta
            </a>
        </div>

        <div class="card shadow border-0">
            <div class="card-header bg-white py-3">
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex flex-wrap gap-2">
                            <div class="input-group me-2" style="max-width: 300px;">
                                <input type="text" wire:model.live.debounce.300ms="search" class="form-control"
                                    placeholder="Buscar por paciente ou médico">
                                <button class="btn btn-outline-primary" type="button">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>

                            <select wire:model.live="status" class="form-select" style="max-width: 170px;">
                                <option value="">Todos os status</option>
                                <option value="agendada">Agendada</option>
                                <option value="confirmada">Confirmada</option>
                                <option value="em_andamento">Em andamento</option>
                                <option value="concluida">Concluída</option>
                                <option value="cancelada">Cancelada</option>
                            </select>

                            <input type="date" wire:model.live="data" class="form-control" style="max-width: 170px;">

                            @if (auth()->user()->tipo == 'admin' || auth()->user()->tipo == 'atendente')
                                <select wire:model.live="medicoId" class="form-select" style="max-width: 200px;">
                                    <option value="">Todos os médicos</option>
                                    @foreach ($this->medicos as $medico)
                                        <option value="{{ $medico->id }}">
                                            Dr(a). {{ $medico->nome }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif

                            <button wire:click="limparFiltros" class="btn btn-outline-secondary" title="Limpar filtros">
                                <i class="fas fa-eraser"></i> Limpar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div wire:loading class="text-center py-3">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Carregando...</span>
                    </div>
                    <p class="mt-2 text-muted">Carregando consultas...</p>
                </div>

                <div wire:loading.remove>
                    @if ($consultas->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Data/Hora</th>
                                        <th>Paciente</th>
                                        <th>Médico</th>
                                        <th>Motivo</th>
                                        <th>Status</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($consultas as $consulta)
                                        <tr>
                                            <td>{{ Carbon\Carbon::parse($consulta->data_hora)->format('d/m/Y H:i') }}
                                            </td>
                                            <td>{{ $consulta->paciente->nome }}</td>
                                            <td>Dr(a). {{ $consulta->medico->nome }}</td>
                                            <td>{{ \Illuminate\Support\Str::limit($consulta->motivo, 30) }}</td>
                                            <td>
                                                @if ($consulta->status === 'agendada')
                                                    <span class="badge bg-warning text-dark">Agendada</span>
                                                @elseif($consulta->status === 'confirmada')
                                                    <span class="badge bg-primary">Confirmada</span>
                                                @elseif($consulta->status === 'em_andamento')
                                                    <span class="badge bg-info">Em andamento</span>
                                                @elseif($consulta->status === 'concluida')
                                                    <span class="badge bg-success">Concluída</span>
                                                @elseif($consulta->status === 'cancelada')
                                                    <span class="badge bg-danger">Cancelada</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('consultas.show', $consulta->id) }}"
                                                        class="btn btn-sm btn-info" title="Visualizar">
                                                        <i class="fas fa-eye"></i>
                                                    </a>

                                                    @if (in_array($consulta->status, ['agendada', 'confirmada']))
                                                        <a href="{{ route('consultas.edit', $consulta->id) }}"
                                                            class="btn btn-sm btn-warning" title="Editar">
                                                            <i class="fas fa-edit"></i>
                                                        </a>

                                                        @if (auth()->user()->tipo == 'medico' && auth()->user()->medico->id == $consulta->medico_id)
                                                            <button wire:click="iniciarAtendimento({{ $consulta->id }})"
                                                                class="btn btn-sm btn-primary"
                                                                title="Iniciar Atendimento">
                                                                <i class="fas fa-play"></i>
                                                            </button>
                                                        @endif

                                                        <button wire:click="confirmarCancelamento({{ $consulta->id }})"
                                                            class="btn btn-sm btn-danger" title="Cancelar">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    @endif

                                                    @if (
                                                        $consulta->status === 'em_andamento' &&
                                                            auth()->user()->tipo == 'medico' &&
                                                            auth()->user()->medico->id == $consulta->medico_id)
                                                        <a href="{{ route('consultas.concluir', $consulta->id) }}"
                                                            class="btn btn-sm btn-success" title="Concluir">
                                                            <i class="fas fa-check"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            {{ $consultas->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <div class="mb-3">
                                <i class="fas fa-calendar-times fa-3x text-muted"></i>
                            </div>
                            <h5>Nenhuma consulta encontrada</h5>
                            <p class="text-muted">Tente ajustar os filtros ou criar uma nova consulta.</p>
                            <a href="{{ route('consultas.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Nova Consulta
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmação para cancelar consulta -->
    @if ($confirmarCancelamento)
        <div class="modal fade show" tabindex="-1" style="display: block; background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirmar cancelamento</h5>
                        <button type="button" class="btn-close"
                            wire:click="$set('confirmarCancelamento', false)"></button>
                    </div>
                    <div class="modal-body">
                        <p>Tem certeza que deseja cancelar esta consulta?</p>
                        <p><strong>Paciente:</strong> {{ $consultaParaCancelar?->paciente->nome }}</p>
                        <p><strong>Data/Hora:</strong>
                            {{ $consultaParaCancelar ? Carbon\Carbon::parse($consultaParaCancelar->data_hora)->format('d/m/Y H:i') : '' }}
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            wire:click="$set('confirmarCancelamento', false)">
                            Não
                        </button>
                        <button type="button" class="btn btn-danger" wire:click="cancelarConsulta">
                            Sim, cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Notificações -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Livewire.on('notify', function(data) {
                const type = data.type;
                const message = data.message;

                // Aqui você pode implementar a notificação como preferir
                // Por exemplo, usando o Bootstrap Toast ou outra biblioteca
                alert(message);
            });
        });
    </script>
</div>

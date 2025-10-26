@extends('layouts.modern')

@section('title', 'Consultas')

@section('breadcrumb')
    <i class="fas fa-home"></i>
    <span>Dashboard</span>
    <i class="fas fa-chevron-right"></i>
    <span>Consultas</span>
@endsection

@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">
                    <i class="fas fa-calendar-check me-2"></i>
                    Consultas
                </h1>
                <p class="page-subtitle">Gerencie agendamentos e consultas médicas</p>
            </div>
            <div>
                <a href="{{ route('consultas.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    Agendar Consulta
                </a>
            </div>
        </div>
    </div>

    <!-- Estatísticas -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stats-card primary">
                <div class="stats-card-body">
                    <div class="stats-card-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stats-card-info">
                        <div class="stats-card-number">{{ $estatisticas['total'] ?? 0 }}</div>
                        <div class="stats-card-label">Total de Consultas</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card success">
                <div class="stats-card-body">
                    <div class="stats-card-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stats-card-info">
                        <div class="stats-card-number">{{ $estatisticas['agendadas'] ?? 0 }}</div>
                        <div class="stats-card-label">Agendadas</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card warning">
                <div class="stats-card-body">
                    <div class="stats-card-icon">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                    <div class="stats-card-info">
                        <div class="stats-card-number">{{ $estatisticas['em_andamento'] ?? 0 }}</div>
                        <div class="stats-card-label">Em Andamento</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card info">
                <div class="stats-card-body">
                    <div class="stats-card-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stats-card-info">
                        <div class="stats-card-number">{{ $estatisticas['concluidas'] ?? 0 }}</div>
                        <div class="stats-card-label">Concluídas</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="busca" class="form-label">Buscar</label>
                    <input type="text" class="form-control" id="busca" name="busca" value="{{ request('busca') }}"
                        placeholder="Nome do paciente, médico...">
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Todos</option>
                        <option value="agendada" {{ request('status') == 'agendada' ? 'selected' : '' }}>Agendada</option>
                        <option value="em_andamento" {{ request('status') == 'em_andamento' ? 'selected' : '' }}>Em
                            Andamento</option>
                        <option value="concluida" {{ request('status') == 'concluida' ? 'selected' : '' }}>Concluída
                        </option>
                        <option value="cancelada" {{ request('status') == 'cancelada' ? 'selected' : '' }}>Cancelada
                        </option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="data_inicio" class="form-label">Data Início</label>
                    <input type="date" class="form-control" id="data_inicio" name="data_inicio"
                        value="{{ request('data_inicio') }}">
                </div>
                <div class="col-md-2">
                    <label for="data_fim" class="form-label">Data Fim</label>
                    <input type="date" class="form-control" id="data_fim" name="data_fim"
                        value="{{ request('data_fim') }}">
                </div>
                <div class="col-md-2">
                    <label for="medico" class="form-label">Médico</label>
                    <select class="form-select" id="medico" name="medico">
                        <option value="">Todos</option>
                        @foreach ($medicos as $medico)
                            <option value="{{ $medico->id }}" {{ request('medico') == $medico->id ? 'selected' : '' }}>
                                {{ $medico->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de Consultas -->
    <div class="card">
        <div class="card-body">
            @if ($consultas->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Data/Hora</th>
                                <th>Paciente</th>
                                <th>Médico</th>
                                <th>Status</th>
                                <th>Tipo</th>
                                <th>Observações</th>
                                <th width="120">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($consultas as $consulta)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $consulta->data_consulta->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $consulta->data_consulta->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-3">
                                                <div class="avatar-title rounded-circle bg-primary">
                                                    {{ substr($consulta->paciente->nome, 0, 1) }}
                                                </div>
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $consulta->paciente->nome }}</div>
                                                <small class="text-muted">{{ $consulta->paciente->cpf }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <div class="fw-bold">{{ $consulta->medico->nome }}</div>
                                            <small class="text-muted">{{ $consulta->medico->especialidade }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $statusClasses = [
                                                'agendada' => 'bg-warning',
                                                'em_andamento' => 'bg-info',
                                                'concluida' => 'bg-success',
                                                'cancelada' => 'bg-danger',
                                            ];
                                            $statusLabels = [
                                                'agendada' => 'Agendada',
                                                'em_andamento' => 'Em Andamento',
                                                'concluida' => 'Concluída',
                                                'cancelada' => 'Cancelada',
                                            ];
                                        @endphp
                                        <span class="badge {{ $statusClasses[$consulta->status] ?? 'bg-secondary' }}">
                                            {{ $statusLabels[$consulta->status] ?? $consulta->status }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($consulta->tipo_consulta == 'primeira_vez')
                                            <span class="badge bg-primary">Primeira Vez</span>
                                        @elseif($consulta->tipo_consulta == 'retorno')
                                            <span class="badge bg-info">Retorno</span>
                                        @elseif($consulta->tipo_consulta == 'urgencia')
                                            <span class="badge bg-danger">Urgência</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $consulta->tipo_consulta }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($consulta->observacoes)
                                            <span class="text-truncate" style="max-width: 150px; display: block;"
                                                title="{{ $consulta->observacoes }}">
                                                {{ $consulta->observacoes }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button"
                                                data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('consultas.show', $consulta) }}">
                                                        <i class="fas fa-eye me-2"></i>
                                                        Visualizar
                                                    </a>
                                                </li>
                                                @if (in_array($consulta->status, ['agendada', 'em_andamento']))
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('consultas.edit', $consulta) }}">
                                                            <i class="fas fa-edit me-2"></i>
                                                            Editar
                                                        </a>
                                                    </li>
                                                @endif
                                                @if ($consulta->status == 'agendada')
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li>
                                                        <button class="dropdown-item text-success"
                                                            onclick="iniciarConsulta({{ $consulta->id }})">
                                                            <i class="fas fa-play me-2"></i>
                                                            Iniciar
                                                        </button>
                                                    </li>
                                                @endif
                                                @if ($consulta->status == 'em_andamento')
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li>
                                                        <button class="dropdown-item text-success"
                                                            onclick="concluirConsulta({{ $consulta->id }})">
                                                            <i class="fas fa-check me-2"></i>
                                                            Concluir
                                                        </button>
                                                    </li>
                                                @endif
                                                @if (in_array($consulta->status, ['agendada']))
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li>
                                                        <button class="dropdown-item text-danger"
                                                            onclick="cancelarConsulta({{ $consulta->id }})">
                                                            <i class="fas fa-times me-2"></i>
                                                            Cancelar
                                                        </button>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginação -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Mostrando {{ $consultas->firstItem() }} a {{ $consultas->lastItem() }}
                        de {{ $consultas->total() }} resultados
                    </div>
                    {{ $consultas->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-calendar-times fa-3x text-muted"></i>
                    </div>
                    <h5 class="text-muted">Nenhuma consulta encontrada</h5>
                    <p class="text-muted mb-4">
                        @if (request()->hasAny(['busca', 'status', 'data_inicio', 'data_fim', 'medico']))
                            Tente ajustar os filtros ou
                        @else
                            Comece agendando uma consulta para ver a lista aqui.
                        @endif
                    </p>
                    <a href="{{ route('consultas.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        Agendar Primeira Consulta
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function iniciarConsulta(id) {
            if (confirm('Tem certeza que deseja iniciar esta consulta?')) {
                fetch(`/consultas/${id}/iniciar`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Erro ao iniciar consulta: ' + data.message);
                        }
                    })
                    .catch(error => {
                        alert('Erro ao iniciar consulta.');
                        console.error('Error:', error);
                    });
            }
        }

        function concluirConsulta(id) {
            if (confirm('Tem certeza que deseja concluir esta consulta?')) {
                fetch(`/consultas/${id}/concluir`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Erro ao concluir consulta: ' + data.message);
                        }
                    })
                    .catch(error => {
                        alert('Erro ao concluir consulta.');
                        console.error('Error:', error);
                    });
            }
        }

        function cancelarConsulta(id) {
            if (confirm('Tem certeza que deseja cancelar esta consulta?')) {
                fetch(`/consultas/${id}/cancelar`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Erro ao cancelar consulta: ' + data.message);
                        }
                    })
                    .catch(error => {
                        alert('Erro ao cancelar consulta.');
                        console.error('Error:', error);
                    });
            }
        }
    </script>
@endsection

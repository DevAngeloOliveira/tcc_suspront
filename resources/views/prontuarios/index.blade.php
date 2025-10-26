@extends('layouts.modern')

@section('title', 'Prontuários')

@section('breadcrumb')
    <i class="fas fa-home"></i>
    <span>Dashboard</span>
    <i class="fas fa-chevron-right"></i>
    <span>Prontuários</span>
@endsection

@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">
                    <i class="fas fa-file-medical me-2"></i>
                    Prontuários Médicos
                </h1>
                <p class="page-subtitle">Gerencie os prontuários e histórico médico dos pacientes</p>
            </div>
            <div>
                <a href="{{ route('prontuarios.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    Novo Prontuário
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
                        <i class="fas fa-file-medical"></i>
                    </div>
                    <div class="stats-card-info">
                        <div class="stats-card-number">{{ $estatisticas['total'] ?? 0 }}</div>
                        <div class="stats-card-label">Total de Prontuários</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card success">
                <div class="stats-card-body">
                    <div class="stats-card-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stats-card-info">
                        <div class="stats-card-number">{{ $estatisticas['hoje'] ?? 0 }}</div>
                        <div class="stats-card-label">Criados Hoje</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card info">
                <div class="stats-card-body">
                    <div class="stats-card-icon">
                        <i class="fas fa-edit"></i>
                    </div>
                    <div class="stats-card-info">
                        <div class="stats-card-number">{{ $estatisticas['recentes'] ?? 0 }}</div>
                        <div class="stats-card-label">Atualizados Recentemente</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card warning">
                <div class="stats-card-body">
                    <div class="stats-card-icon">
                        <i class="fas fa-user-patients"></i>
                    </div>
                    <div class="stats-card-info">
                        <div class="stats-card-number">{{ $estatisticas['pacientes_ativos'] ?? 0 }}</div>
                        <div class="stats-card-label">Pacientes com Prontuário</div>
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
                        placeholder="Nome do paciente, diagnóstico...">
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
                    <label for="ordenar" class="form-label">Ordenar por</label>
                    <select class="form-select" id="ordenar" name="ordenar">
                        <option value="data_desc" {{ request('ordenar') == 'data_desc' ? 'selected' : '' }}>Data (Recente)
                        </option>
                        <option value="data_asc" {{ request('ordenar') == 'data_asc' ? 'selected' : '' }}>Data (Antigo)
                        </option>
                        <option value="paciente" {{ request('ordenar') == 'paciente' ? 'selected' : '' }}>Paciente A-Z
                        </option>
                        <option value="medico" {{ request('ordenar') == 'medico' ? 'selected' : '' }}>Médico A-Z</option>
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

    <!-- Lista de Prontuários -->
    <div class="card">
        <div class="card-body">
            @if ($prontuarios->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Paciente</th>
                                <th>Médico</th>
                                <th>Queixa Principal</th>
                                <th>Diagnóstico</th>
                                <th>Consulta</th>
                                <th width="120">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($prontuarios as $prontuario)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $prontuario->created_at->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $prontuario->created_at->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-3">
                                                <div class="avatar-title rounded-circle bg-primary">
                                                    {{ substr($prontuario->paciente->nome, 0, 1) }}
                                                </div>
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $prontuario->paciente->nome }}</div>
                                                <small class="text-muted">{{ $prontuario->paciente->cpf }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <div class="fw-bold">{{ $prontuario->medico->nome }}</div>
                                            <small class="text-muted">{{ $prontuario->medico->especialidade }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($prontuario->queixa_principal)
                                            <span class="text-truncate" style="max-width: 200px; display: block;"
                                                title="{{ $prontuario->queixa_principal }}">
                                                {{ $prontuario->queixa_principal }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($prontuario->diagnostico)
                                            <span class="text-truncate" style="max-width: 200px; display: block;"
                                                title="{{ $prontuario->diagnostico }}">
                                                {{ $prontuario->diagnostico }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($prontuario->consulta)
                                            <a href="{{ route('consultas.show', $prontuario->consulta) }}"
                                                class="btn btn-outline-info btn-sm">
                                                Consulta #{{ $prontuario->consulta->id }}
                                            </a>
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
                                                        href="{{ route('prontuarios.show', $prontuario) }}">
                                                        <i class="fas fa-eye me-2"></i>
                                                        Visualizar
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('prontuarios.edit', $prontuario) }}">
                                                        <i class="fas fa-edit me-2"></i>
                                                        Editar
                                                    </a>
                                                </li>
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                <li>
                                                    <button class="dropdown-item text-primary"
                                                        onclick="imprimirProntuario({{ $prontuario->id }})">
                                                        <i class="fas fa-print me-2"></i>
                                                        Imprimir
                                                    </button>
                                                </li>
                                                @if ($prontuario->consulta)
                                                    <li>
                                                        <a class="dropdown-item text-info"
                                                            href="{{ route('consultas.show', $prontuario->consulta) }}">
                                                            <i class="fas fa-calendar-check me-2"></i>
                                                            Ver Consulta
                                                        </a>
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
                        Mostrando {{ $prontuarios->firstItem() }} a {{ $prontuarios->lastItem() }}
                        de {{ $prontuarios->total() }} resultados
                    </div>
                    {{ $prontuarios->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-file-medical fa-3x text-muted"></i>
                    </div>
                    <h5 class="text-muted">Nenhum prontuário encontrado</h5>
                    <p class="text-muted mb-4">
                        @if (request()->hasAny(['busca', 'medico', 'data_inicio', 'data_fim']))
                            Tente ajustar os filtros ou
                        @else
                            Comece criando um prontuário para ver a lista aqui.
                        @endif
                    </p>
                    <a href="{{ route('prontuarios.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        Criar Primeiro Prontuário
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function imprimirProntuario(id) {
            // Abrir página de impressão do prontuário
            window.open(`/prontuarios/${id}/imprimir`, '_blank');
        }
    </script>
@endsection

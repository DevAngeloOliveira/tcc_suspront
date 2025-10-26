@extends('layouts.modern')

@section('title', 'Exames')

@section('breadcrumb')
    <i class="fas fa-home"></i>
    <span>Dashboard</span>
    <i class="fas fa-chevron-right"></i>
    <span>Exames</span>
@endsection

@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">
                    <i class="fas fa-microscope me-2"></i>
                    Gestão de Exames
                </h1>
                <p class="page-subtitle">
                    Gerencie exames médicos, resultados e solicitações
                </p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('exames.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    Novo Exame
                </a>
                <button class="btn btn-outline-secondary" onclick="exportarExames()">
                    <i class="fas fa-download me-2"></i>
                    Exportar
                </button>
            </div>
        </div>
    </div>

    <!-- Estatísticas -->
    <div class="row g-4 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="stats-card stats-primary">
                <div class="stats-icon">
                    <i class="fas fa-vial"></i>
                </div>
                <div class="stats-info">
                    <h3>{{ $totalExames ?? 0 }}</h3>
                    <p>Total de Exames</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stats-card stats-warning">
                <div class="stats-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stats-info">
                    <h3>{{ $examesPendentes ?? 0 }}</h3>
                    <p>Pendentes</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stats-card stats-info">
                <div class="stats-icon">
                    <i class="fas fa-flask"></i>
                </div>
                <div class="stats-info">
                    <h3>{{ $examesEmAndamento ?? 0 }}</h3>
                    <p>Em Andamento</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stats-card stats-success">
                <div class="stats-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stats-info">
                    <h3>{{ $examesConcluidos ?? 0 }}</h3>
                    <p>Concluídos</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('exames.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label">Buscar</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" class="form-control" id="search" name="search"
                            value="{{ request('search') }}" placeholder="Paciente, tipo de exame...">
                    </div>
                </div>

                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Todos</option>
                        <option value="solicitado" {{ request('status') == 'solicitado' ? 'selected' : '' }}>Solicitado
                        </option>
                        <option value="agendado" {{ request('status') == 'agendado' ? 'selected' : '' }}>Agendado</option>
                        <option value="em_andamento" {{ request('status') == 'em_andamento' ? 'selected' : '' }}>Em
                            Andamento</option>
                        <option value="concluido" {{ request('status') == 'concluido' ? 'selected' : '' }}>Concluído
                        </option>
                        <option value="cancelado" {{ request('status') == 'cancelado' ? 'selected' : '' }}>Cancelado
                        </option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="tipo" class="form-label">Tipo</label>
                    <select class="form-select" id="tipo" name="tipo">
                        <option value="">Todos</option>
                        <option value="laboratorio" {{ request('tipo') == 'laboratorio' ? 'selected' : '' }}>Laboratório
                        </option>
                        <option value="imagem" {{ request('tipo') == 'imagem' ? 'selected' : '' }}>Imagem</option>
                        <option value="cardiologico" {{ request('tipo') == 'cardiologico' ? 'selected' : '' }}>Cardiológico
                        </option>
                        <option value="neurologia" {{ request('tipo') == 'neurologia' ? 'selected' : '' }}>Neurologia
                        </option>
                        <option value="outros" {{ request('tipo') == 'outros' ? 'selected' : '' }}>Outros</option>
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

                <div class="col-md-1">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="fas fa-filter"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de Exames -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Lista de Exames</h5>
                <div class="d-flex gap-2">
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button"
                            data-bs-toggle="dropdown">
                            <i class="fas fa-sort me-2"></i>Ordenar
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item"
                                    href="{{ request()->fullUrlWithQuery(['sort' => 'data_exame', 'order' => 'desc']) }}">Mais
                                    Recentes</a></li>
                            <li><a class="dropdown-item"
                                    href="{{ request()->fullUrlWithQuery(['sort' => 'data_exame', 'order' => 'asc']) }}">Mais
                                    Antigos</a></li>
                            <li><a class="dropdown-item"
                                    href="{{ request()->fullUrlWithQuery(['sort' => 'paciente', 'order' => 'asc']) }}">Paciente
                                    A-Z</a></li>
                            <li><a class="dropdown-item"
                                    href="{{ request()->fullUrlWithQuery(['sort' => 'tipo', 'order' => 'asc']) }}">Tipo</a>
                            </li>
                            <li><a class="dropdown-item"
                                    href="{{ request()->fullUrlWithQuery(['sort' => 'status', 'order' => 'asc']) }}">Status</a>
                            </li>
                        </ul>
                    </div>
                    <button class="btn btn-outline-secondary btn-sm" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>Imprimir
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            @if ($exames->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 80px">#</th>
                                <th>Paciente</th>
                                <th>Tipo de Exame</th>
                                <th>Data do Exame</th>
                                <th>Status</th>
                                <th>Médico Solicitante</th>
                                <th>Laboratório/Local</th>
                                <th style="width: 120px">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($exames as $exame)
                                <tr>
                                    <td>
                                        <span class="badge bg-light text-dark">#{{ $exame->id }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2">
                                                <div class="avatar-title rounded-circle bg-primary">
                                                    {{ substr($exame->paciente->nome, 0, 1) }}
                                                </div>
                                            </div>
                                            <div>
                                                <div class="fw-medium">{{ $exame->paciente->nome }}</div>
                                                <small class="text-muted">{{ $exame->paciente->cpf }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-medium">{{ $exame->tipo_exame }}</div>
                                        @if ($exame->categoria)
                                            <small class="text-muted">{{ ucfirst($exame->categoria) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div>{{ $exame->data_exame ? $exame->data_exame->format('d/m/Y') : '-' }}</div>
                                        @if ($exame->hora_exame)
                                            <small class="text-muted">{{ $exame->hora_exame }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @switch($exame->status)
                                            @case('solicitado')
                                                <span class="badge bg-warning">Solicitado</span>
                                            @break

                                            @case('agendado')
                                                <span class="badge bg-info">Agendado</span>
                                            @break

                                            @case('em_andamento')
                                                <span class="badge bg-primary">Em Andamento</span>
                                            @break

                                            @case('concluido')
                                                <span class="badge bg-success">Concluído</span>
                                            @break

                                            @case('cancelado')
                                                <span class="badge bg-danger">Cancelado</span>
                                            @break

                                            @default
                                                <span class="badge bg-secondary">{{ ucfirst($exame->status) }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        <div>{{ $exame->medico->nome }}</div>
                                        <small class="text-muted">{{ $exame->medico->especialidade }}</small>
                                    </td>
                                    <td>
                                        <div>{{ $exame->laboratorio ?: 'SUS - Sistema Local' }}</div>
                                        @if ($exame->local_exame)
                                            <small class="text-muted">{{ $exame->local_exame }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('exames.show', $exame) }}" class="btn btn-outline-info"
                                                title="Visualizar">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('exames.edit', $exame) }}" class="btn btn-outline-primary"
                                                title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                                                    data-bs-toggle="dropdown" title="Mais opções">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    @if ($exame->status == 'solicitado')
                                                        <li><a class="dropdown-item" href="#"
                                                                onclick="atualizarStatus({{ $exame->id }}, 'agendado')">
                                                                <i class="fas fa-calendar me-2"></i>Agendar
                                                            </a></li>
                                                    @endif
                                                    @if (in_array($exame->status, ['agendado', 'em_andamento']))
                                                        <li><a class="dropdown-item" href="#"
                                                                onclick="atualizarStatus({{ $exame->id }}, 'concluido')">
                                                                <i class="fas fa-check me-2"></i>Concluir
                                                            </a></li>
                                                    @endif
                                                    @if ($exame->status == 'concluido')
                                                        <li><a class="dropdown-item" href="#"
                                                                onclick="imprimirResultado({{ $exame->id }})">
                                                                <i class="fas fa-print me-2"></i>Imprimir Resultado
                                                            </a></li>
                                                    @endif
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li><a class="dropdown-item text-danger" href="#"
                                                            onclick="confirmarExclusao({{ $exame->id }})">
                                                            <i class="fas fa-trash me-2"></i>Excluir
                                                        </a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginação -->
                @if ($exames->hasPages())
                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted">
                                Mostrando {{ $exames->firstItem() }} a {{ $exames->lastItem() }} de
                                {{ $exames->total() }} exames
                            </div>
                            {{ $exames->links() }}
                        </div>
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-microscope fa-3x text-muted"></i>
                    </div>
                    <h5 class="text-muted">Nenhum exame encontrado</h5>
                    <p class="text-muted">
                        @if (request()->filled('search') || request()->filled('status') || request()->filled('tipo'))
                            Tente ajustar os filtros ou
                            <a href="{{ route('exames.index') }}">limpar a busca</a>
                        @else
                            Comece criando um novo exame
                        @endif
                    </p>
                    @if (!request()->filled('search') && !request()->filled('status') && !request()->filled('tipo'))
                        <a href="{{ route('exames.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>
                            Criar Primeiro Exame
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Exames Urgentes (se houver) -->
    @if (isset($examesUrgentes) && $examesUrgentes->count() > 0)
        <div class="card mt-4">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Exames Urgentes - Atenção Necessária
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @foreach ($examesUrgentes as $exame)
                        <div class="col-md-6">
                            <div class="alert alert-danger d-flex align-items-center">
                                <i class="fas fa-exclamation-triangle me-3"></i>
                                <div class="flex-grow-1">
                                    <strong>{{ $exame->paciente->nome }}</strong><br>
                                    <small>{{ $exame->tipo_exame }} - Agendado para
                                        {{ $exame->data_exame->format('d/m/Y') }}</small>
                                </div>
                                <a href="{{ route('exames.show', $exame) }}" class="btn btn-outline-danger btn-sm">
                                    Ver Detalhes
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
@endsection

@section('scripts')
    <script>
        function atualizarStatus(exameId, novoStatus) {
            if (confirm('Deseja atualizar o status deste exame?')) {
                // Implementar atualização via AJAX
                fetch(`/exames/${exameId}/status`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            status: novoStatus
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Erro ao atualizar status');
                        }
                    })
                    .catch(error => {
                        console.error('Erro:', error);
                        alert('Erro ao atualizar status');
                    });
            }
        }

        function confirmarExclusao(exameId) {
            if (confirm('Tem certeza que deseja excluir este exame? Esta ação não pode ser desfeita.')) {
                // Implementar exclusão
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/exames/${exameId}`;
                form.innerHTML = `
                @csrf
                @method('DELETE')
            `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        function imprimirResultado(exameId) {
            window.open(`/exames/${exameId}/resultado`, '_blank');
        }

        function exportarExames() {
            const params = new URLSearchParams(window.location.search);
            params.set('export', 'true');
            window.location.href = '{{ route('exames.index') }}?' + params.toString();
        }

        // Auto-refresh para status em tempo real (opcional)
        let autoRefreshEnabled = false;

        function toggleAutoRefresh() {
            autoRefreshEnabled = !autoRefreshEnabled;
            if (autoRefreshEnabled) {
                setInterval(() => {
                    if (document.hasFocus()) {
                        location.reload();
                    }
                }, 60000); // 1 minuto
            }
        }

        // Filtros em tempo real
        let searchTimeout;
        document.getElementById('search').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                this.form.submit();
            }, 500);
        });
    </script>
@endsection

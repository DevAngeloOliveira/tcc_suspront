@extends('layouts.modern')

@section('title', 'Médicos')

@section('breadcrumb')
    <i class="fas fa-home"></i>
    <span>Dashboard</span>
    <i class="fas fa-chevron-right"></i>
    <span>Médicos</span>
@endsection

@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">
                    <i class="fas fa-user-md me-2"></i>
                    Gestão de Médicos
                </h1>
                <p class="page-subtitle">
                    Gerencie profissionais médicos e especialistas
                </p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('medicos.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    Novo Médico
                </a>
                <button class="btn btn-outline-secondary" onclick="exportarMedicos()">
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
                    <i class="fas fa-user-md"></i>
                </div>
                <div class="stats-info">
                    <h3>{{ $totalMedicos ?? 0 }}</h3>
                    <p>Total de Médicos</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stats-card stats-success">
                <div class="stats-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stats-info">
                    <h3>{{ $medicosAtivos ?? 0 }}</h3>
                    <p>Ativos</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stats-card stats-info">
                <div class="stats-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="stats-info">
                    <h3>{{ $medicosPlantao ?? 0 }}</h3>
                    <p>Em Plantão</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stats-card stats-warning">
                <div class="stats-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="stats-info">
                    <h3>{{ $especialidades ?? 0 }}</h3>
                    <p>Especialidades</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('medicos.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label">Buscar</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" class="form-control" id="search" name="search"
                            value="{{ request('search') }}" placeholder="Nome, CRM, especialidade...">
                    </div>
                </div>

                <div class="col-md-2">
                    <label for="especialidade" class="form-label">Especialidade</label>
                    <select class="form-select" id="especialidade" name="especialidade">
                        <option value="">Todas</option>
                        <option value="clinica_geral" {{ request('especialidade') == 'clinica_geral' ? 'selected' : '' }}>
                            Clínica Geral</option>
                        <option value="cardiologia" {{ request('especialidade') == 'cardiologia' ? 'selected' : '' }}>
                            Cardiologia</option>
                        <option value="pediatria" {{ request('especialidade') == 'pediatria' ? 'selected' : '' }}>Pediatria
                        </option>
                        <option value="ginecologia" {{ request('especialidade') == 'ginecologia' ? 'selected' : '' }}>
                            Ginecologia</option>
                        <option value="ortopedia" {{ request('especialidade') == 'ortopedia' ? 'selected' : '' }}>Ortopedia
                        </option>
                        <option value="dermatologia" {{ request('especialidade') == 'dermatologia' ? 'selected' : '' }}>
                            Dermatologia</option>
                        <option value="psiquiatria" {{ request('especialidade') == 'psiquiatria' ? 'selected' : '' }}>
                            Psiquiatria</option>
                        <option value="neurologia" {{ request('especialidade') == 'neurologia' ? 'selected' : '' }}>
                            Neurologia</option>
                        <option value="oftalmologia" {{ request('especialidade') == 'oftalmologia' ? 'selected' : '' }}>
                            Oftalmologia</option>
                        <option value="otorrinolaringologia"
                            {{ request('especialidade') == 'otorrinolaringologia' ? 'selected' : '' }}>Otorrinolaringologia
                        </option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Todos</option>
                        <option value="ativo" {{ request('status') == 'ativo' ? 'selected' : '' }}>Ativo</option>
                        <option value="inativo" {{ request('status') == 'inativo' ? 'selected' : '' }}>Inativo</option>
                        <option value="licenca" {{ request('status') == 'licenca' ? 'selected' : '' }}>Em Licença</option>
                        <option value="ferias" {{ request('status') == 'ferias' ? 'selected' : '' }}>Férias</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="plantao" class="form-label">Plantão</label>
                    <select class="form-select" id="plantao" name="plantao">
                        <option value="">Todos</option>
                        <option value="sim" {{ request('plantao') == 'sim' ? 'selected' : '' }}>Em Plantão</option>
                        <option value="nao" {{ request('plantao') == 'nao' ? 'selected' : '' }}>Fora de Plantão</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="turno" class="form-label">Turno</label>
                    <select class="form-select" id="turno" name="turno">
                        <option value="">Todos</option>
                        <option value="manha" {{ request('turno') == 'manha' ? 'selected' : '' }}>Manhã</option>
                        <option value="tarde" {{ request('turno') == 'tarde' ? 'selected' : '' }}>Tarde</option>
                        <option value="noite" {{ request('turno') == 'noite' ? 'selected' : '' }}>Noite</option>
                        <option value="integral" {{ request('turno') == 'integral' ? 'selected' : '' }}>Integral</option>
                    </select>
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

    <!-- Lista de Médicos -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Lista de Médicos</h5>
                <div class="d-flex gap-2">
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button"
                            data-bs-toggle="dropdown">
                            <i class="fas fa-sort me-2"></i>Ordenar
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item"
                                    href="{{ request()->fullUrlWithQuery(['sort' => 'nome', 'order' => 'asc']) }}">Nome
                                    A-Z</a></li>
                            <li><a class="dropdown-item"
                                    href="{{ request()->fullUrlWithQuery(['sort' => 'nome', 'order' => 'desc']) }}">Nome
                                    Z-A</a></li>
                            <li><a class="dropdown-item"
                                    href="{{ request()->fullUrlWithQuery(['sort' => 'especialidade', 'order' => 'asc']) }}">Especialidade</a>
                            </li>
                            <li><a class="dropdown-item"
                                    href="{{ request()->fullUrlWithQuery(['sort' => 'crm', 'order' => 'asc']) }}">CRM</a>
                            </li>
                            <li><a class="dropdown-item"
                                    href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'order' => 'desc']) }}">Mais
                                    Recentes</a></li>
                        </ul>
                    </div>
                    <button class="btn btn-outline-secondary btn-sm" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>Imprimir
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            @if ($medicos->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 80px">#</th>
                                <th>Médico</th>
                                <th>CRM</th>
                                <th>Especialidade</th>
                                <th>Contato</th>
                                <th>Status</th>
                                <th>Plantão</th>
                                <th style="width: 120px">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($medicos as $medico)
                                <tr>
                                    <td>
                                        <span class="badge bg-light text-dark">#{{ $medico->id }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2">
                                                <div class="avatar-title rounded-circle bg-success">
                                                    {{ substr($medico->nome, 0, 1) }}
                                                </div>
                                            </div>
                                            <div>
                                                <div class="fw-medium">{{ $medico->nome }}</div>
                                                <small class="text-muted">{{ $medico->cpf }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-medium">{{ $medico->crm }}</div>
                                        @if ($medico->crm_uf)
                                            <small class="text-muted">{{ $medico->crm_uf }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-medium">{{ $medico->especialidade }}</div>
                                        @if ($medico->sub_especialidade)
                                            <small class="text-muted">{{ $medico->sub_especialidade }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div>{{ $medico->telefone ?: '-' }}</div>
                                        @if ($medico->email)
                                            <small class="text-muted">{{ $medico->email }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @switch($medico->status)
                                            @case('ativo')
                                                <span class="badge bg-success">Ativo</span>
                                            @break

                                            @case('inativo')
                                                <span class="badge bg-secondary">Inativo</span>
                                            @break

                                            @case('licenca')
                                                <span class="badge bg-warning">Licença</span>
                                            @break

                                            @case('ferias')
                                                <span class="badge bg-info">Férias</span>
                                            @break

                                            @default
                                                <span class="badge bg-secondary">{{ ucfirst($medico->status) }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        @if ($medico->em_plantao)
                                            <span class="badge bg-primary">
                                                <i class="fas fa-clock me-1"></i>
                                                {{ ucfirst($medico->turno_atual ?: 'Plantão') }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">Fora</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('medicos.show', $medico) }}" class="btn btn-outline-info"
                                                title="Visualizar">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('medicos.edit', $medico) }}"
                                                class="btn btn-outline-primary" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                                                    data-bs-toggle="dropdown" title="Mais opções">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    @if ($medico->status == 'ativo')
                                                        @if (!$medico->em_plantao)
                                                            <li><a class="dropdown-item" href="#"
                                                                    onclick="iniciarPlantao({{ $medico->id }})">
                                                                    <i class="fas fa-play me-2"></i>Iniciar Plantão
                                                                </a></li>
                                                        @else
                                                            <li><a class="dropdown-item" href="#"
                                                                    onclick="finalizarPlantao({{ $medico->id }})">
                                                                    <i class="fas fa-stop me-2"></i>Finalizar Plantão
                                                                </a></li>
                                                        @endif
                                                    @endif
                                                    <li><a class="dropdown-item" href="#"
                                                            onclick="verEscala({{ $medico->id }})">
                                                            <i class="fas fa-calendar me-2"></i>Ver Escala
                                                        </a></li>
                                                    <li><a class="dropdown-item" href="#"
                                                            onclick="verConsultas({{ $medico->id }})">
                                                            <i class="fas fa-stethoscope me-2"></i>Ver Consultas
                                                        </a></li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    @if ($medico->status == 'ativo')
                                                        <li><a class="dropdown-item text-warning" href="#"
                                                                onclick="atualizarStatus({{ $medico->id }}, 'inativo')">
                                                                <i class="fas fa-pause me-2"></i>Inativar
                                                            </a></li>
                                                    @else
                                                        <li><a class="dropdown-item text-success" href="#"
                                                                onclick="atualizarStatus({{ $medico->id }}, 'ativo')">
                                                                <i class="fas fa-play me-2"></i>Ativar
                                                            </a></li>
                                                    @endif
                                                    <li><a class="dropdown-item text-danger" href="#"
                                                            onclick="confirmarExclusao({{ $medico->id }})">
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
                @if ($medicos->hasPages())
                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted">
                                Mostrando {{ $medicos->firstItem() }} a {{ $medicos->lastItem() }} de
                                {{ $medicos->total() }} médicos
                            </div>
                            {{ $medicos->links() }}
                        </div>
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-user-md fa-3x text-muted"></i>
                    </div>
                    <h5 class="text-muted">Nenhum médico encontrado</h5>
                    <p class="text-muted">
                        @if (request()->filled('search') || request()->filled('especialidade') || request()->filled('status'))
                            Tente ajustar os filtros ou
                            <a href="{{ route('medicos.index') }}">limpar a busca</a>
                        @else
                            Comece cadastrando um novo médico
                        @endif
                    </p>
                    @if (!request()->filled('search') && !request()->filled('especialidade') && !request()->filled('status'))
                        <a href="{{ route('medicos.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>
                            Cadastrar Primeiro Médico
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Médicos em Plantão Atual -->
    @if (isset($medicosPlantaoAtual) && $medicosPlantaoAtual->count() > 0)
        <div class="card mt-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-clock me-2"></i>
                    Médicos em Plantão Atual
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @foreach ($medicosPlantaoAtual as $medico)
                        <div class="col-md-6 col-lg-4">
                            <div class="card border-primary">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-lg me-3">
                                            <div class="avatar-title rounded-circle bg-primary">
                                                {{ substr($medico->nome, 0, 1) }}
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-bold">{{ $medico->nome }}</div>
                                            <small class="text-muted">{{ $medico->especialidade }}</small>
                                            <div class="mt-1">
                                                <span class="badge bg-primary">{{ ucfirst($medico->turno_atual) }}</span>
                                                @if ($medico->inicio_plantao)
                                                    <small class="text-muted ms-2">
                                                        desde {{ $medico->inicio_plantao->format('H:i') }}
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                        <div>
                                            <a href="{{ route('medicos.show', $medico) }}"
                                                class="btn btn-outline-primary btn-sm">
                                                Ver Detalhes
                                            </a>
                                        </div>
                                    </div>
                                </div>
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
        function atualizarStatus(medicoId, novoStatus) {
            const statusTextos = {
                'ativo': 'ativar',
                'inativo': 'inativar',
                'licenca': 'colocar em licença',
                'ferias': 'colocar em férias'
            };

            if (confirm(`Deseja ${statusTextos[novoStatus]} este médico?`)) {
                fetch(`/medicos/${medicoId}/status`, {
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
                            alert('Erro ao atualizar status: ' + (data.message || 'Erro desconhecido'));
                        }
                    })
                    .catch(error => {
                        console.error('Erro:', error);
                        alert('Erro ao atualizar status');
                    });
            }
        }

        function iniciarPlantao(medicoId) {
            const turno = prompt('Qual turno? (manha, tarde, noite, integral)', 'integral');

            if (turno && ['manha', 'tarde', 'noite', 'integral'].includes(turno)) {
                fetch(`/medicos/${medicoId}/plantao`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            turno: turno
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Erro ao iniciar plantão: ' + (data.message || 'Erro desconhecido'));
                        }
                    })
                    .catch(error => {
                        console.error('Erro:', error);
                        alert('Erro ao iniciar plantão');
                    });
            }
        }

        function finalizarPlantao(medicoId) {
            if (confirm('Deseja finalizar o plantão deste médico?')) {
                fetch(`/medicos/${medicoId}/plantao`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Erro ao finalizar plantão: ' + (data.message || 'Erro desconhecido'));
                        }
                    })
                    .catch(error => {
                        console.error('Erro:', error);
                        alert('Erro ao finalizar plantão');
                    });
            }
        }

        function verEscala(medicoId) {
            window.open(`/medicos/${medicoId}/escala`, '_blank');
        }

        function verConsultas(medicoId) {
            window.location.href = `/consultas?medico_id=${medicoId}`;
        }

        function confirmarExclusao(medicoId) {
            if (confirm('Tem certeza que deseja excluir este médico? Esta ação não pode ser desfeita.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/medicos/${medicoId}`;
                form.innerHTML = `
                @csrf
                @method('DELETE')
            `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        function exportarMedicos() {
            const params = new URLSearchParams(window.location.search);
            params.set('export', 'true');
            window.location.href = '{{ route('medicos.index') }}?' + params.toString();
        }

        // Auto-refresh para atualizações de plantão em tempo real
        setInterval(function() {
            if (document.hasFocus()) {
                // Verificar se houve mudanças nos plantões
                fetch('/api/medicos/plantao-status')
                    .then(response => response.json())
                    .then(data => {
                        // Atualizar indicadores de plantão se necessário
                        data.forEach(medico => {
                            const row = document.querySelector(`tr[data-medico-id="${medico.id}"]`);
                            if (row) {
                                const plantaoCell = row.querySelector('td:nth-child(7)');
                                if (plantaoCell) {
                                    plantaoCell.innerHTML = medico.em_plantao ?
                                        `<span class="badge bg-primary"><i class="fas fa-clock me-1"></i>${medico.turno_atual || 'Plantão'}</span>` :
                                        '<span class="badge bg-secondary">Fora</span>';
                                }
                            }
                        });
                    })
                    .catch(error => {
                        console.error('Erro ao verificar plantões:', error);
                    });
            }
        }, 60000); // 1 minuto

        // Filtros em tempo real
        let searchTimeout;
        document.getElementById('search').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                this.form.submit();
            }, 500);
        });

        // Adicionar data-medico-id às linhas da tabela
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('tbody tr').forEach(function(row, index) {
                const medicoId = @json($medicos->pluck('id'));
                if (medicoId[index]) {
                    row.setAttribute('data-medico-id', medicoId[index]);
                }
            });
        });
    </script>
@endsection

@extends('layouts.modern')

@section('title', 'Consulta #' . $consulta->id)

@section('breadcrumb')
    <i class="fas fa-home"></i>
    <span>Dashboard</span>
    <i class="fas fa-chevron-right"></i>
    <a href="{{ route('consultas.index') }}">Consultas</a>
    <i class="fas fa-chevron-right"></i>
    <span>Consulta #{{ $consulta->id }}</span>
@endsection

@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">
                    <i class="fas fa-calendar-check me-2"></i>
                    Consulta #{{ $consulta->id }}
                </h1>
                <p class="page-subtitle">
                    {{ $consulta->data_consulta->format('d/m/Y \à\s H:i') }} -
                    {{ $consulta->paciente->nome }}
                </p>
            </div>
            <div class="d-flex gap-2">
                @if (in_array($consulta->status, ['agendada', 'em_andamento']))
                    <a href="{{ route('consultas.edit', $consulta) }}" class="btn btn-outline-primary">
                        <i class="fas fa-edit me-2"></i>
                        Editar
                    </a>
                @endif
                <a href="{{ route('consultas.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Voltar
                </a>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <!-- Status da Consulta -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">Status da Consulta</h5>
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
                            <span class="badge {{ $statusClasses[$consulta->status] ?? 'bg-secondary' }} fs-6">
                                {{ $statusLabels[$consulta->status] ?? $consulta->status }}
                            </span>
                        </div>
                        <div>
                            @if ($consulta->status == 'agendada')
                                <button class="btn btn-success btn-sm" onclick="iniciarConsulta()">
                                    <i class="fas fa-play me-2"></i>
                                    Iniciar Consulta
                                </button>
                            @elseif($consulta->status == 'em_andamento')
                                <button class="btn btn-success btn-sm" onclick="concluirConsulta()">
                                    <i class="fas fa-check me-2"></i>
                                    Concluir Consulta
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informações da Consulta -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Informações da Consulta
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Data e Horário</label>
                            <div class="fw-bold">{{ $consulta->data_consulta->format('d/m/Y \à\s H:i') }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Tipo de Consulta</label>
                            <div>
                                @if ($consulta->tipo_consulta == 'primeira_vez')
                                    <span class="badge bg-primary">Primeira Vez</span>
                                @elseif($consulta->tipo_consulta == 'retorno')
                                    <span class="badge bg-info">Retorno</span>
                                @elseif($consulta->tipo_consulta == 'urgencia')
                                    <span class="badge bg-danger">Urgência</span>
                                @elseif($consulta->tipo_consulta == 'preventiva')
                                    <span class="badge bg-success">Preventiva</span>
                                @else
                                    <span class="badge bg-secondary">{{ $consulta->tipo_consulta }}</span>
                                @endif
                            </div>
                        </div>
                        @if ($consulta->motivo)
                            <div class="col-12">
                                <label class="form-label text-muted">Motivo da Consulta</label>
                                <div class="fw-bold">{{ $consulta->motivo }}</div>
                            </div>
                        @endif
                        @if ($consulta->observacoes)
                            <div class="col-12">
                                <label class="form-label text-muted">Observações</label>
                                <div class="bg-light p-3 rounded">{{ $consulta->observacoes }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Prontuário da Consulta -->
            @if ($consulta->prontuario)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-file-medical me-2"></i>
                            Prontuário da Consulta
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @if ($consulta->prontuario->queixa_principal)
                                <div class="col-12">
                                    <label class="form-label text-muted">Queixa Principal</label>
                                    <div class="bg-light p-3 rounded">{{ $consulta->prontuario->queixa_principal }}</div>
                                </div>
                            @endif
                            @if ($consulta->prontuario->historia_doenca_atual)
                                <div class="col-12">
                                    <label class="form-label text-muted">História da Doença Atual</label>
                                    <div class="bg-light p-3 rounded">{{ $consulta->prontuario->historia_doenca_atual }}
                                    </div>
                                </div>
                            @endif
                            @if ($consulta->prontuario->exame_fisico)
                                <div class="col-12">
                                    <label class="form-label text-muted">Exame Físico</label>
                                    <div class="bg-light p-3 rounded">{{ $consulta->prontuario->exame_fisico }}</div>
                                </div>
                            @endif
                            @if ($consulta->prontuario->diagnostico)
                                <div class="col-12">
                                    <label class="form-label text-muted">Diagnóstico</label>
                                    <div class="bg-light p-3 rounded">{{ $consulta->prontuario->diagnostico }}</div>
                                </div>
                            @endif
                            @if ($consulta->prontuario->plano_tratamento)
                                <div class="col-12">
                                    <label class="form-label text-muted">Plano de Tratamento</label>
                                    <div class="bg-light p-3 rounded">{{ $consulta->prontuario->plano_tratamento }}</div>
                                </div>
                            @endif
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('prontuarios.show', $consulta->prontuario) }}"
                                class="btn btn-outline-info btn-sm">
                                <i class="fas fa-external-link-alt me-2"></i>
                                Ver Prontuário Completo
                            </a>
                        </div>
                    </div>
                </div>
            @else
                @if ($consulta->status == 'em_andamento')
                    <div class="card mb-4">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="fas fa-file-medical fa-3x text-muted"></i>
                            </div>
                            <h5 class="text-muted">Prontuário não preenchido</h5>
                            <p class="text-muted mb-4">
                                Esta consulta ainda não possui prontuário. Inicie o preenchimento para registrar as
                                informações médicas.
                            </p>
                            <a href="{{ route('prontuarios.create', ['consulta_id' => $consulta->id]) }}"
                                class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>
                                Criar Prontuário
                            </a>
                        </div>
                    </div>
                @endif
            @endif

            <!-- Receitas -->
            @if ($consulta->receitas->count() > 0)
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-prescription me-2"></i>
                                Receitas Médicas
                            </h5>
                            @if ($consulta->status == 'em_andamento')
                                <a href="{{ route('receitas.create', ['consulta_id' => $consulta->id]) }}"
                                    class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus me-2"></i>
                                    Nova Receita
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            @foreach ($consulta->receitas as $receita)
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div>
                                        <div class="fw-bold">{{ $receita->medicamento }}</div>
                                        <small class="text-muted">{{ $receita->dosagem }} -
                                            {{ $receita->frequencia }}</small>
                                    </div>
                                    <div>
                                        <a href="{{ route('receitas.show', $receita) }}"
                                            class="btn btn-outline-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                @if ($consulta->status == 'em_andamento')
                    <div class="card mb-4">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="fas fa-prescription fa-3x text-muted"></i>
                            </div>
                            <h5 class="text-muted">Nenhuma receita prescrita</h5>
                            <p class="text-muted mb-4">
                                Ainda não foram prescritas receitas para esta consulta.
                            </p>
                            <a href="{{ route('receitas.create', ['consulta_id' => $consulta->id]) }}"
                                class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>
                                Prescrever Receita
                            </a>
                        </div>
                    </div>
                @endif
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Informações do Paciente -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-user me-2"></i>
                        Paciente
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-lg me-3">
                            <div class="avatar-title rounded-circle bg-primary">
                                {{ substr($consulta->paciente->nome, 0, 1) }}
                            </div>
                        </div>
                        <div>
                            <div class="fw-bold">{{ $consulta->paciente->nome }}</div>
                            <small class="text-muted">{{ $consulta->paciente->cpf }}</small>
                        </div>
                    </div>

                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between px-0">
                            <span>Idade:</span>
                            <span>{{ $consulta->paciente->data_nascimento->age }} anos</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between px-0">
                            <span>Cartão SUS:</span>
                            <span class="small">{{ $consulta->paciente->cartao_sus }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between px-0">
                            <span>Telefone:</span>
                            <span>{{ $consulta->paciente->telefone ?: '-' }}</span>
                        </div>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('pacientes.show', $consulta->paciente) }}"
                            class="btn btn-outline-info btn-sm w-100">
                            <i class="fas fa-user me-2"></i>
                            Ver Perfil Completo
                        </a>
                    </div>
                </div>
            </div>

            <!-- Informações do Médico -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-user-md me-2"></i>
                        Médico
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-lg me-3">
                            <div class="avatar-title rounded-circle bg-success">
                                {{ substr($consulta->medico->nome, 0, 1) }}
                            </div>
                        </div>
                        <div>
                            <div class="fw-bold">{{ $consulta->medico->nome }}</div>
                            <small class="text-muted">{{ $consulta->medico->especialidade }}</small>
                        </div>
                    </div>

                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between px-0">
                            <span>CRM:</span>
                            <span>{{ $consulta->medico->crm }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between px-0">
                            <span>Telefone:</span>
                            <span>{{ $consulta->medico->telefone ?: '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ações Rápidas -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt me-2"></i>
                        Ações Rápidas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if ($consulta->status == 'agendada')
                            <button class="btn btn-success" onclick="iniciarConsulta()">
                                <i class="fas fa-play me-2"></i>
                                Iniciar Consulta
                            </button>
                        @elseif($consulta->status == 'em_andamento')
                            @if (!$consulta->prontuario)
                                <a href="{{ route('prontuarios.create', ['consulta_id' => $consulta->id]) }}"
                                    class="btn btn-primary">
                                    <i class="fas fa-file-medical me-2"></i>
                                    Criar Prontuário
                                </a>
                            @endif
                            <a href="{{ route('receitas.create', ['consulta_id' => $consulta->id]) }}"
                                class="btn btn-outline-primary">
                                <i class="fas fa-prescription me-2"></i>
                                Nova Receita
                            </a>
                            <button class="btn btn-success" onclick="concluirConsulta()">
                                <i class="fas fa-check me-2"></i>
                                Concluir Consulta
                            </button>
                        @endif

                        @if (in_array($consulta->status, ['agendada']))
                            <hr>
                            <button class="btn btn-outline-danger" onclick="cancelarConsulta()">
                                <i class="fas fa-times me-2"></i>
                                Cancelar Consulta
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Histórico -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i>
                        Histórico
                    </h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <div class="fw-bold">Consulta agendada</div>
                                <small class="text-muted">{{ $consulta->created_at->format('d/m/Y H:i') }}</small>
                            </div>
                        </div>
                        @if ($consulta->status != 'agendada')
                            <div class="timeline-item">
                                <div class="timeline-marker bg-info"></div>
                                <div class="timeline-content">
                                    <div class="fw-bold">Consulta iniciada</div>
                                    <small class="text-muted">{{ $consulta->updated_at->format('d/m/Y H:i') }}</small>
                                </div>
                            </div>
                        @endif
                        @if ($consulta->status == 'concluida')
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <div class="fw-bold">Consulta concluída</div>
                                    <small class="text-muted">{{ $consulta->updated_at->format('d/m/Y H:i') }}</small>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function iniciarConsulta() {
            if (confirm('Tem certeza que deseja iniciar esta consulta?')) {
                fetch(`/consultas/{{ $consulta->id }}/iniciar`, {
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

        function concluirConsulta() {
            if (confirm('Tem certeza que deseja concluir esta consulta?')) {
                fetch(`/consultas/{{ $consulta->id }}/concluir`, {
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

        function cancelarConsulta() {
            if (confirm('Tem certeza que deseja cancelar esta consulta?')) {
                fetch(`/consultas/{{ $consulta->id }}/cancelar`, {
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

@section('styles')
    <style>
        .timeline {
            position: relative;
            padding-left: 20px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 10px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: var(--bs-border-color);
        }

        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }

        .timeline-marker {
            position: absolute;
            left: -15px;
            top: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 2px solid white;
        }

        .timeline-content {
            padding-left: 15px;
        }
    </style>
@endsection

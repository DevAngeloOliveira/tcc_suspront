@extends('layouts.modern')

@section('title', 'Exame #' . $exame->id)

@section('breadcrumb')
    <i class="fas fa-home"></i>
    <span>Dashboard</span>
    <i class="fas fa-chevron-right"></i>
    <a href="{{ route('exames.index') }}">Exames</a>
    <i class="fas fa-chevron-right"></i>
    <span>Exame #{{ $exame->id }}</span>
@endsection

@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">
                    <i class="fas fa-microscope me-2"></i>
                    Exame #{{ $exame->id }}
                </h1>
                <p class="page-subtitle">
                    {{ $exame->tipo_exame }} - {{ $exame->paciente->nome }}
                </p>
            </div>
            <div class="d-flex gap-2">
                @if ($exame->status == 'concluido')
                    <a href="{{ route('exames.resultado', $exame) }}" target="_blank" class="btn btn-outline-success">
                        <i class="fas fa-file-medical me-2"></i>
                        Ver Resultado
                    </a>
                @endif
                <a href="{{ route('exames.imprimir', $exame) }}" target="_blank" class="btn btn-outline-info">
                    <i class="fas fa-print me-2"></i>
                    Imprimir
                </a>
                <a href="{{ route('exames.edit', $exame) }}" class="btn btn-outline-primary">
                    <i class="fas fa-edit me-2"></i>
                    Editar
                </a>
                <a href="{{ route('exames.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Voltar
                </a>
            </div>
        </div>
    </div>

    <!-- Status e Prioridade -->
    <div class="row g-4 mb-4">
        <div class="col-lg-3">
            <div class="card text-center">
                <div class="card-body">
                    <div class="mb-2">
                        @switch($exame->status)
                            @case('solicitado')
                                <span class="badge bg-warning fs-6">Solicitado</span>
                            @break

                            @case('agendado')
                                <span class="badge bg-info fs-6">Agendado</span>
                            @break

                            @case('em_andamento')
                                <span class="badge bg-primary fs-6">Em Andamento</span>
                            @break

                            @case('concluido')
                                <span class="badge bg-success fs-6">Concluído</span>
                            @break

                            @case('cancelado')
                                <span class="badge bg-danger fs-6">Cancelado</span>
                            @break
                        @endswitch
                    </div>
                    <small class="text-muted">Status do Exame</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card text-center">
                <div class="card-body">
                    <div class="mb-2">
                        @switch($exame->prioridade)
                            @case('normal')
                                <span class="badge bg-secondary fs-6">Normal</span>
                            @break

                            @case('urgente')
                                <span class="badge bg-warning fs-6">Urgente</span>
                            @break

                            @case('muito_urgente')
                                <span class="badge bg-danger fs-6">Muito Urgente</span>
                            @break
                        @endswitch
                    </div>
                    <small class="text-muted">Prioridade</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card text-center">
                <div class="card-body">
                    <div class="mb-2">
                        <span class="badge bg-info fs-6">{{ ucfirst($exame->categoria) }}</span>
                    </div>
                    <small class="text-muted">Categoria</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card text-center">
                <div class="card-body">
                    <div class="mb-2">
                        @if ($exame->jejum_necessario)
                            <span class="badge bg-warning fs-6">
                                <i class="fas fa-clock me-1"></i>Jejum {{ $exame->tempo_jejum }}h
                            </span>
                        @else
                            <span class="badge bg-secondary fs-6">Sem Jejum</span>
                        @endif
                    </div>
                    <small class="text-muted">Preparo</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <!-- Informações do Exame -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-microscope me-2"></i>
                        Detalhes do Exame
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label text-muted">Tipo de Exame</label>
                            <div class="fw-bold fs-5">{{ $exame->tipo_exame }}</div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label text-muted">Categoria</label>
                            <div class="fw-bold">
                                <span class="badge bg-info fs-6">{{ ucfirst($exame->categoria) }}</span>
                            </div>
                        </div>

                        @if ($exame->descricao)
                            <div class="col-12">
                                <label class="form-label text-muted">Descrição/Indicação</label>
                                <div class="bg-light p-3 rounded">{{ $exame->descricao }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Agendamento -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar me-2"></i>
                        Informações de Agendamento
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label text-muted">Data do Exame</label>
                            <div class="fw-bold">
                                @if ($exame->data_exame)
                                    {{ $exame->data_exame->format('d/m/Y') }}
                                    @if ($exame->data_exame->isToday())
                                        <span class="badge bg-primary ms-2">Hoje</span>
                                    @elseif($exame->data_exame->isTomorrow())
                                        <span class="badge bg-info ms-2">Amanhã</span>
                                    @elseif($exame->data_exame->isPast())
                                        <span class="badge bg-secondary ms-2">Passado</span>
                                    @endif
                                @else
                                    <span class="text-muted">Não agendado</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label text-muted">Horário</label>
                            <div class="fw-bold">
                                {{ $exame->hora_exame ?: 'Não definido' }}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label text-muted">Status</label>
                            <div class="fw-bold">
                                @switch($exame->status)
                                    @case('solicitado')
                                        <span class="text-warning">Aguardando Agendamento</span>
                                    @break

                                    @case('agendado')
                                        <span class="text-info">Agendado</span>
                                    @break

                                    @case('em_andamento')
                                        <span class="text-primary">Em Realização</span>
                                    @break

                                    @case('concluido')
                                        <span class="text-success">Finalizado</span>
                                    @break

                                    @case('cancelado')
                                        <span class="text-danger">Cancelado</span>
                                    @break
                                @endswitch
                            </div>
                        </div>

                        @if ($exame->laboratorio || $exame->local_exame)
                            <div class="col-md-6">
                                <label class="form-label text-muted">Laboratório/Local</label>
                                <div class="fw-bold">{{ $exame->laboratorio ?: 'SUS - Sistema Local' }}</div>
                                @if ($exame->local_exame)
                                    <small class="text-muted">{{ $exame->local_exame }}</small>
                                @endif
                            </div>
                        @endif

                        @if ($exame->jejum_necessario)
                            <div class="col-md-6">
                                <label class="form-label text-muted">Preparo Necessário</label>
                                <div class="fw-bold text-warning">
                                    <i class="fas fa-clock me-1"></i>
                                    Jejum de {{ $exame->tempo_jejum ?? 8 }} horas
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Preparo e Orientações -->
            @if ($exame->preparo || $exame->observacoes_preparo)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-clipboard-list me-2"></i>
                            Orientações de Preparo
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($exame->preparo)
                            <div class="mb-3">
                                <label class="form-label text-muted">Instruções de Preparo</label>
                                <div class="bg-light p-3 rounded">{{ $exame->preparo }}</div>
                            </div>
                        @endif

                        @if ($exame->observacoes_preparo)
                            <div class="mb-0">
                                <label class="form-label text-muted">Observações Adicionais</label>
                                <div class="bg-light p-3 rounded">{{ $exame->observacoes_preparo }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Resultados -->
            @if ($exame->status == 'concluido')
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-file-medical me-2"></i>
                            Resultados do Exame
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($exame->resultado)
                            <div class="mb-3">
                                <label class="form-label text-muted">Resultado</label>
                                <div class="bg-light p-3 rounded">{{ $exame->resultado }}</div>
                            </div>
                        @endif

                        @if ($exame->observacoes_resultado)
                            <div class="mb-3">
                                <label class="form-label text-muted">Observações do Resultado</label>
                                <div class="bg-light p-3 rounded">{{ $exame->observacoes_resultado }}</div>
                            </div>
                        @endif

                        @if ($exame->valores_referencia)
                            <div class="mb-3">
                                <label class="form-label text-muted">Valores de Referência</label>
                                <div class="bg-info bg-opacity-10 p-3 rounded border border-info">
                                    {{ $exame->valores_referencia }}</div>
                            </div>
                        @endif

                        <div class="d-flex gap-2">
                            <a href="{{ route('exames.resultado', $exame) }}" target="_blank" class="btn btn-success">
                                <i class="fas fa-file-medical me-2"></i>
                                Visualizar Resultado Completo
                            </a>
                            <a href="{{ route('exames.imprimir-resultado', $exame) }}" target="_blank"
                                class="btn btn-outline-success">
                                <i class="fas fa-print me-2"></i>
                                Imprimir Resultado
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Histórico de Status -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i>
                        Histórico do Exame
                    </h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Exame Solicitado</h6>
                                <p class="timeline-description">
                                    Solicitado por {{ $exame->medico->nome }}
                                </p>
                                <small class="timeline-time text-muted">
                                    {{ $exame->created_at->format('d/m/Y H:i') }}
                                </small>
                            </div>
                        </div>

                        @if ($exame->data_exame)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-info"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">Exame Agendado</h6>
                                    <p class="timeline-description">
                                        Agendado para {{ $exame->data_exame->format('d/m/Y') }}
                                        @if ($exame->hora_exame)
                                            às {{ $exame->hora_exame }}
                                        @endif
                                    </p>
                                    <small class="timeline-time text-muted">
                                        {{ $exame->updated_at->format('d/m/Y H:i') }}
                                    </small>
                                </div>
                            </div>
                        @endif

                        @if ($exame->status == 'concluido')
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">Exame Concluído</h6>
                                    <p class="timeline-description">
                                        Resultado disponível
                                    </p>
                                    <small class="timeline-time text-muted">
                                        {{ $exame->updated_at->format('d/m/Y H:i') }}
                                    </small>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Informações do Paciente -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-user me-2"></i>
                        Dados do Paciente
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-lg me-3">
                            <div class="avatar-title rounded-circle bg-primary">
                                {{ substr($exame->paciente->nome, 0, 1) }}
                            </div>
                        </div>
                        <div>
                            <div class="fw-bold">{{ $exame->paciente->nome }}</div>
                            <small class="text-muted">{{ $exame->paciente->cpf }}</small>
                        </div>
                    </div>

                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between px-0">
                            <span>Idade:</span>
                            <span>{{ $exame->paciente->data_nascimento->age }} anos</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between px-0">
                            <span>Sexo:</span>
                            <span>{{ $exame->paciente->sexo == 'M' ? 'Masculino' : 'Feminino' }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between px-0">
                            <span>Cartão SUS:</span>
                            <span class="small">{{ $exame->paciente->cartao_sus }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between px-0">
                            <span>Telefone:</span>
                            <span>{{ $exame->paciente->telefone ?: '-' }}</span>
                        </div>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('pacientes.show', $exame->paciente) }}"
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
                        Médico Solicitante
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-lg me-3">
                            <div class="avatar-title rounded-circle bg-success">
                                {{ substr($exame->medico->nome, 0, 1) }}
                            </div>
                        </div>
                        <div>
                            <div class="fw-bold">{{ $exame->medico->nome }}</div>
                            <small class="text-muted">{{ $exame->medico->especialidade }}</small>
                        </div>
                    </div>

                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between px-0">
                            <span>CRM:</span>
                            <span>{{ $exame->medico->crm }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between px-0">
                            <span>Telefone:</span>
                            <span>{{ $exame->medico->telefone ?: '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Consulta Relacionada -->
            @if ($exame->consulta)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-calendar-check me-2"></i>
                            Consulta Relacionada
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between px-0">
                                <span>Consulta:</span>
                                <span class="badge bg-info">#{{ $exame->consulta->id }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between px-0">
                                <span>Data:</span>
                                <span>{{ $exame->consulta->data_consulta->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between px-0">
                                <span>Status:</span>
                                <span class="badge bg-success">{{ ucfirst($exame->consulta->status) }}</span>
                            </div>
                        </div>

                        <div class="mt-3">
                            <a href="{{ route('consultas.show', $exame->consulta) }}"
                                class="btn btn-outline-success btn-sm w-100">
                                <i class="fas fa-calendar-check me-2"></i>
                                Ver Consulta
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Ações Rápidas -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt me-2"></i>
                        Ações Rápidas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if ($exame->status != 'concluido' && $exame->status != 'cancelado')
                            <button class="btn btn-primary"
                                onclick="atualizarStatus('{{ $exame->id }}', '{{ $exame->status == 'solicitado' ? 'agendado' : 'concluido' }}')">
                                <i class="fas fa-{{ $exame->status == 'solicitado' ? 'calendar' : 'check' }} me-2"></i>
                                {{ $exame->status == 'solicitado' ? 'Agendar Exame' : 'Concluir Exame' }}
                            </button>
                        @endif

                        <a href="{{ route('exames.edit', $exame) }}" class="btn btn-outline-primary">
                            <i class="fas fa-edit me-2"></i>
                            Editar Exame
                        </a>

                        <a href="{{ route('exames.imprimir', $exame) }}" target="_blank" class="btn btn-outline-info">
                            <i class="fas fa-print me-2"></i>
                            Imprimir Solicitação
                        </a>

                        @if ($exame->status == 'concluido')
                            <a href="{{ route('exames.resultado', $exame) }}" target="_blank"
                                class="btn btn-outline-success">
                                <i class="fas fa-file-medical me-2"></i>
                                Ver Resultado
                            </a>
                        @endif

                        @if ($exame->consulta)
                            <a href="{{ route('consultas.show', $exame->consulta) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-calendar-check me-2"></i>
                                Ver Consulta
                            </a>
                        @endif

                        <a href="{{ route('pacientes.show', $exame->paciente) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-user me-2"></i>
                            Perfil do Paciente
                        </a>
                    </div>
                </div>
            </div>

            <!-- Informações do Sistema -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Informações do Sistema
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between px-0">
                            <span>Solicitado em:</span>
                            <span class="small">{{ $exame->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between px-0">
                            <span>Última atualização:</span>
                            <span class="small">{{ $exame->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between px-0">
                            <span>ID do Exame:</span>
                            <span class="badge bg-secondary">#{{ $exame->id }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function atualizarStatus(exameId, novoStatus) {
            const statusTextos = {
                'agendado': 'agendar',
                'em_andamento': 'iniciar',
                'concluido': 'concluir',
                'cancelado': 'cancelar'
            };

            if (confirm(`Deseja ${statusTextos[novoStatus]} este exame?`)) {
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
                            alert('Erro ao atualizar status: ' + (data.message || 'Erro desconhecido'));
                        }
                    })
                    .catch(error => {
                        console.error('Erro:', error);
                        alert('Erro ao atualizar status');
                    });
            }
        }

        // Auto-refresh para atualizações em tempo real (opcional)
        setInterval(function() {
            if (document.hasFocus()) {
                // Verificar se houve atualizações no exame
                fetch(`/api/exames/{{ $exame->id }}/status`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.updated_at !== '{{ $exame->updated_at->toISOString() }}') {
                            // Mostrar notificação de atualização
                            const toast = document.createElement('div');
                            toast.className = 'toast-container position-fixed top-0 end-0 p-3';
                            toast.innerHTML = `
                            <div class="toast show" role="alert">
                                <div class="toast-header">
                                    <i class="fas fa-info-circle text-info me-2"></i>
                                    <strong class="me-auto">Atualização Disponível</strong>
                                    <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
                                </div>
                                <div class="toast-body">
                                    O exame foi atualizado. <a href="#" onclick="location.reload()">Recarregar página</a>
                                </div>
                            </div>
                        `;
                            document.body.appendChild(toast);
                        }
                    })
                    .catch(error => {
                        console.error('Erro ao verificar atualizações:', error);
                    });
            }
        }, 30000); // 30 segundos
    </script>
@endsection

@section('styles')
    <style>
        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e9ecef;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 30px;
        }

        .timeline-marker {
            position: absolute;
            left: -23px;
            top: 0;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            border: 3px solid #fff;
            box-shadow: 0 0 0 2px #e9ecef;
        }

        .timeline-content {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border-left: 3px solid #0d6efd;
        }

        .timeline-title {
            margin: 0 0 5px 0;
            font-size: 14px;
            font-weight: 600;
        }

        .timeline-description {
            margin: 0 0 10px 0;
            font-size: 13px;
            color: #6c757d;
        }

        .timeline-time {
            font-size: 12px;
        }
    </style>
@endsection

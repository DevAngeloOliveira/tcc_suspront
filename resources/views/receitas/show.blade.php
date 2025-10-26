@extends('layouts.modern')

@section('title', 'Receita #' . $receita->id)

@section('breadcrumb')
    <i class="fas fa-home"></i>
    <span>Dashboard</span>
    <i class="fas fa-chevron-right"></i>
    <a href="{{ route('receitas.index') }}">Receitas</a>
    <i class="fas fa-chevron-right"></i>
    <span>Receita #{{ $receita->id }}</span>
@endsection

@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">
                    <i class="fas fa-prescription me-2"></i>
                    Receita #{{ $receita->id }}
                </h1>
                <p class="page-subtitle">
                    {{ $receita->medicamento }} - {{ $receita->paciente->nome }}
                </p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('receitas.imprimir', $receita) }}" target="_blank" class="btn btn-outline-info">
                    <i class="fas fa-print me-2"></i>
                    Imprimir
                </a>
                <a href="{{ route('receitas.edit', $receita) }}" class="btn btn-outline-primary">
                    <i class="fas fa-edit me-2"></i>
                    Editar
                </a>
                <a href="{{ route('receitas.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Voltar
                </a>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <!-- Informações do Medicamento -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-pills me-2"></i>
                        Informações do Medicamento
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label text-muted">Nome do Medicamento</label>
                            <div class="fw-bold fs-5">{{ $receita->medicamento }}</div>
                            @if ($receita->principio_ativo)
                                <small class="text-muted">Princípio Ativo: {{ $receita->principio_ativo }}</small>
                            @endif
                        </div>

                        @if ($receita->forma_farmaceutica)
                            <div class="col-md-4">
                                <label class="form-label text-muted">Forma Farmacêutica</label>
                                <div class="fw-bold">
                                    <span class="badge bg-info fs-6">{{ ucfirst($receita->forma_farmaceutica) }}</span>
                                </div>
                            </div>
                        @endif

                        @if ($receita->concentracao)
                            <div class="col-md-6">
                                <label class="form-label text-muted">Concentração</label>
                                <div class="fw-bold">{{ $receita->concentracao }}</div>
                            </div>
                        @endif

                        @if ($receita->quantidade)
                            <div class="col-md-6">
                                <label class="form-label text-muted">Quantidade Total</label>
                                <div class="fw-bold">{{ $receita->quantidade }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Posologia -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-clock me-2"></i>
                        Posologia
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label text-muted">Dosagem</label>
                            <div class="fw-bold">
                                <span class="badge bg-primary fs-6">{{ $receita->dosagem }}</span>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label text-muted">Frequência</label>
                            <div class="fw-bold">
                                <span class="badge bg-secondary fs-6">{{ $receita->frequencia }}</span>
                            </div>
                        </div>

                        @if ($receita->duracao)
                            <div class="col-md-4">
                                <label class="form-label text-muted">Duração do Tratamento</label>
                                <div class="fw-bold">{{ $receita->duracao }}</div>
                            </div>
                        @endif

                        @if ($receita->via_administracao)
                            <div class="col-md-6">
                                <label class="form-label text-muted">Via de Administração</label>
                                <div class="fw-bold">{{ ucfirst(str_replace('_', ' ', $receita->via_administracao)) }}
                                </div>
                            </div>
                        @endif

                        <!-- Características especiais -->
                        <div class="col-md-6">
                            <label class="form-label text-muted">Características</label>
                            <div class="d-flex gap-2">
                                @if ($receita->uso_continuo)
                                    <span class="badge bg-warning">Uso Contínuo</span>
                                @endif
                                @if ($receita->controle_especial)
                                    <span class="badge bg-danger">Controle Especial</span>
                                @endif
                                @if (!$receita->uso_continuo && !$receita->controle_especial)
                                    <span class="text-muted">-</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Orientações -->
            @if ($receita->orientacoes || $receita->observacoes)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Orientações e Observações
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($receita->orientacoes)
                            <div class="mb-3">
                                <label class="form-label text-muted">Orientações de Uso</label>
                                <div class="bg-light p-3 rounded">{{ $receita->orientacoes }}</div>
                            </div>
                        @endif

                        @if ($receita->observacoes)
                            <div class="mb-0">
                                <label class="form-label text-muted">Observações Adicionais</label>
                                <div class="bg-light p-3 rounded">{{ $receita->observacoes }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Receita Formatada -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-file-prescription me-2"></i>
                        Receita Formatada
                    </h5>
                </div>
                <div class="card-body">
                    <div class="border rounded p-4 bg-light" id="receita-formatada">
                        <div class="text-center mb-4">
                            <h4 class="mb-1">RECEITA MÉDICA</h4>
                            <hr>
                        </div>

                        <div class="mb-3">
                            <strong>Paciente:</strong> {{ $receita->paciente->nome }}<br>
                            <strong>CPF:</strong> {{ $receita->paciente->cpf }}<br>
                            <strong>Data de Nascimento:</strong> {{ $receita->paciente->data_nascimento->format('d/m/Y') }}
                        </div>

                        <div class="mb-4">
                            <h5>Rp/</h5>
                            <div class="ps-3">
                                <div class="fw-bold">{{ $receita->medicamento }}</div>
                                @if ($receita->principio_ativo && $receita->concentracao)
                                    <div>{{ $receita->principio_ativo }} {{ $receita->concentracao }}</div>
                                @endif
                                @if ($receita->forma_farmaceutica)
                                    <div>{{ ucfirst($receita->forma_farmaceutica) }}</div>
                                @endif
                                @if ($receita->quantidade)
                                    <div>{{ $receita->quantidade }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="mb-4">
                            <strong>Modo de Usar:</strong><br>
                            {{ $receita->dosagem }}, {{ $receita->frequencia }}
                            @if ($receita->duracao)
                                , por {{ $receita->duracao }}
                            @endif
                            @if ($receita->via_administracao)
                                , via {{ str_replace('_', ' ', $receita->via_administracao) }}
                            @endif
                            @if ($receita->orientacoes)
                                <br>{{ $receita->orientacoes }}
                            @endif
                        </div>

                        @if ($receita->observacoes)
                            <div class="mb-4">
                                <strong>Observações:</strong><br>
                                {{ $receita->observacoes }}
                            </div>
                        @endif

                        <div class="row mt-5">
                            <div class="col-6">
                                <small class="text-muted">
                                    Data: {{ $receita->created_at->format('d/m/Y') }}
                                </small>
                            </div>
                            <div class="col-6 text-end">
                                <div class="border-top pt-2">
                                    <strong>{{ $receita->medico->nome }}</strong><br>
                                    <small>{{ $receita->medico->especialidade }}</small><br>
                                    <small>CRM: {{ $receita->medico->crm }}</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 text-center">
                        <button class="btn btn-outline-primary" onclick="imprimirReceita()">
                            <i class="fas fa-print me-2"></i>
                            Imprimir Esta Receita
                        </button>
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
                                {{ substr($receita->paciente->nome, 0, 1) }}
                            </div>
                        </div>
                        <div>
                            <div class="fw-bold">{{ $receita->paciente->nome }}</div>
                            <small class="text-muted">{{ $receita->paciente->cpf }}</small>
                        </div>
                    </div>

                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between px-0">
                            <span>Idade:</span>
                            <span>{{ $receita->paciente->data_nascimento->age }} anos</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between px-0">
                            <span>Sexo:</span>
                            <span>{{ $receita->paciente->sexo == 'M' ? 'Masculino' : 'Feminino' }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between px-0">
                            <span>Cartão SUS:</span>
                            <span class="small">{{ $receita->paciente->cartao_sus }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between px-0">
                            <span>Telefone:</span>
                            <span>{{ $receita->paciente->telefone ?: '-' }}</span>
                        </div>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('pacientes.show', $receita->paciente) }}"
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
                        Médico Prescritor
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-lg me-3">
                            <div class="avatar-title rounded-circle bg-success">
                                {{ substr($receita->medico->nome, 0, 1) }}
                            </div>
                        </div>
                        <div>
                            <div class="fw-bold">{{ $receita->medico->nome }}</div>
                            <small class="text-muted">{{ $receita->medico->especialidade }}</small>
                        </div>
                    </div>

                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between px-0">
                            <span>CRM:</span>
                            <span>{{ $receita->medico->crm }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between px-0">
                            <span>Telefone:</span>
                            <span>{{ $receita->medico->telefone ?: '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informações da Consulta -->
            @if ($receita->consulta)
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
                                <span class="badge bg-info">#{{ $receita->consulta->id }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between px-0">
                                <span>Data:</span>
                                <span>{{ $receita->consulta->data_consulta->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between px-0">
                                <span>Status:</span>
                                <span class="badge bg-success">{{ ucfirst($receita->consulta->status) }}</span>
                            </div>
                        </div>

                        <div class="mt-3">
                            <a href="{{ route('consultas.show', $receita->consulta) }}"
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
                        <a href="{{ route('receitas.edit', $receita) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>
                            Editar Receita
                        </a>
                        <a href="{{ route('receitas.imprimir', $receita) }}" target="_blank"
                            class="btn btn-outline-info">
                            <i class="fas fa-print me-2"></i>
                            Imprimir Receita
                        </a>
                        @if ($receita->consulta)
                            <a href="{{ route('consultas.show', $receita->consulta) }}" class="btn btn-outline-success">
                                <i class="fas fa-calendar-check me-2"></i>
                                Ver Consulta
                            </a>
                        @endif
                        <a href="{{ route('pacientes.show', $receita->paciente) }}" class="btn btn-outline-secondary">
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
                            <span>Prescrita em:</span>
                            <span class="small">{{ $receita->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between px-0">
                            <span>Última atualização:</span>
                            <span class="small">{{ $receita->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between px-0">
                            <span>ID da Receita:</span>
                            <span class="badge bg-secondary">#{{ $receita->id }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function imprimirReceita() {
            // Abrir página de impressão da receita
            window.open(`/receitas/{{ $receita->id }}/imprimir`, '_blank');
        }
    </script>
@endsection

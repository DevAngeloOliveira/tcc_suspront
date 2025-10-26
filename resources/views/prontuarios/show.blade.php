@extends('layouts.modern')

@section('title', 'Prontuário #' . $prontuario->id)

@section('breadcrumb')
    <i class="fas fa-home"></i>
    <span>Dashboard</span>
    <i class="fas fa-chevron-right"></i>
    <a href="{{ route('prontuarios.index') }}">Prontuários</a>
    <i class="fas fa-chevron-right"></i>
    <span>Prontuário #{{ $prontuario->id }}</span>
@endsection

@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">
                    <i class="fas fa-file-medical me-2"></i>
                    Prontuário #{{ $prontuario->id }}
                </h1>
                <p class="page-subtitle">
                    {{ $prontuario->paciente->nome }} - {{ $prontuario->created_at->format('d/m/Y \à\s H:i') }}
                </p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-info" onclick="imprimirProntuario()">
                    <i class="fas fa-print me-2"></i>
                    Imprimir
                </button>
                <a href="{{ route('prontuarios.edit', $prontuario) }}" class="btn btn-outline-primary">
                    <i class="fas fa-edit me-2"></i>
                    Editar
                </a>
                <a href="{{ route('prontuarios.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Voltar
                </a>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <!-- Dados Básicos -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Informações Básicas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label text-muted">Data do Atendimento</label>
                            <div class="fw-bold">{{ $prontuario->created_at->format('d/m/Y \à\s H:i') }}</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted">Médico Responsável</label>
                            <div class="fw-bold">{{ $prontuario->medico->nome }}</div>
                            <small class="text-muted">{{ $prontuario->medico->especialidade }} - CRM:
                                {{ $prontuario->medico->crm }}</small>
                        </div>
                        @if ($prontuario->consulta)
                            <div class="col-md-4">
                                <label class="form-label text-muted">Consulta Relacionada</label>
                                <div>
                                    <a href="{{ route('consultas.show', $prontuario->consulta) }}"
                                        class="btn btn-outline-info btn-sm">
                                        <i class="fas fa-calendar-check me-2"></i>
                                        Consulta #{{ $prontuario->consulta->id }}
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Anamnese -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-comments me-2"></i>
                        Anamnese
                    </h5>
                </div>
                <div class="card-body">
                    @if ($prontuario->queixa_principal)
                        <div class="mb-4">
                            <label class="form-label text-muted">Queixa Principal</label>
                            <div class="bg-light p-3 rounded">{{ $prontuario->queixa_principal }}</div>
                        </div>
                    @endif

                    @if ($prontuario->historia_doenca_atual)
                        <div class="mb-4">
                            <label class="form-label text-muted">História da Doença Atual</label>
                            <div class="bg-light p-3 rounded">{{ $prontuario->historia_doenca_atual }}</div>
                        </div>
                    @endif

                    @if ($prontuario->historia_pregressa)
                        <div class="mb-4">
                            <label class="form-label text-muted">História Pregressa</label>
                            <div class="bg-light p-3 rounded">{{ $prontuario->historia_pregressa }}</div>
                        </div>
                    @endif

                    @if ($prontuario->historia_familiar)
                        <div class="mb-4">
                            <label class="form-label text-muted">História Familiar</label>
                            <div class="bg-light p-3 rounded">{{ $prontuario->historia_familiar }}</div>
                        </div>
                    @endif

                    @if ($prontuario->historia_social)
                        <div class="mb-0">
                            <label class="form-label text-muted">História Social</label>
                            <div class="bg-light p-3 rounded">{{ $prontuario->historia_social }}</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Exame Físico -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-stethoscope me-2"></i>
                        Exame Físico
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Sinais Vitais -->
                    @if ($prontuario->peso || $prontuario->altura || $prontuario->pressao_arterial || $prontuario->temperatura)
                        <div class="row g-3 mb-4">
                            @if ($prontuario->peso)
                                <div class="col-md-3">
                                    <label class="form-label text-muted">Peso</label>
                                    <div class="fw-bold">{{ $prontuario->peso }} kg</div>
                                </div>
                            @endif
                            @if ($prontuario->altura)
                                <div class="col-md-3">
                                    <label class="form-label text-muted">Altura</label>
                                    <div class="fw-bold">{{ $prontuario->altura }} cm</div>
                                </div>
                            @endif
                            @if ($prontuario->peso && $prontuario->altura)
                                <div class="col-md-3">
                                    <label class="form-label text-muted">IMC</label>
                                    @php
                                        $imc = $prontuario->peso / ($prontuario->altura / 100) ** 2;
                                        $imcFormatado = number_format($imc, 1);
                                        $classificacao = '';
                                        $corClass = '';

                                        if ($imc < 18.5) {
                                            $classificacao = 'Abaixo do peso';
                                            $corClass = 'text-warning';
                                        } elseif ($imc < 25) {
                                            $classificacao = 'Peso normal';
                                            $corClass = 'text-success';
                                        } elseif ($imc < 30) {
                                            $classificacao = 'Sobrepeso';
                                            $corClass = 'text-warning';
                                        } else {
                                            $classificacao = 'Obesidade';
                                            $corClass = 'text-danger';
                                        }
                                    @endphp
                                    <div class="fw-bold {{ $corClass }}">{{ $imcFormatado }}</div>
                                    <small class="{{ $corClass }}">{{ $classificacao }}</small>
                                </div>
                            @endif
                            @if ($prontuario->pressao_arterial)
                                <div class="col-md-3">
                                    <label class="form-label text-muted">Pressão Arterial</label>
                                    <div class="fw-bold">{{ $prontuario->pressao_arterial }} mmHg</div>
                                </div>
                            @endif
                            @if ($prontuario->temperatura)
                                <div class="col-md-3">
                                    <label class="form-label text-muted">Temperatura</label>
                                    <div class="fw-bold">{{ $prontuario->temperatura }}°C</div>
                                </div>
                            @endif
                        </div>
                    @endif

                    @if ($prontuario->exame_fisico)
                        <div>
                            <label class="form-label text-muted">Exame Físico Detalhado</label>
                            <div class="bg-light p-3 rounded">{{ $prontuario->exame_fisico }}</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Avaliação e Conduta -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-diagnoses me-2"></i>
                        Avaliação e Conduta Médica
                    </h5>
                </div>
                <div class="card-body">
                    @if ($prontuario->diagnostico)
                        <div class="mb-4">
                            <label class="form-label text-muted">Diagnóstico</label>
                            <div class="bg-primary bg-opacity-10 p-3 rounded border border-primary border-opacity-25">
                                {{ $prontuario->diagnostico }}
                            </div>
                        </div>
                    @endif

                    @if ($prontuario->plano_tratamento)
                        <div class="mb-4">
                            <label class="form-label text-muted">Plano de Tratamento</label>
                            <div class="bg-light p-3 rounded">{{ $prontuario->plano_tratamento }}</div>
                        </div>
                    @endif

                    @if ($prontuario->observacoes)
                        <div class="mb-0">
                            <label class="form-label text-muted">Observações Gerais</label>
                            <div class="bg-light p-3 rounded">{{ $prontuario->observacoes }}</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Receitas Prescritas -->
            @if ($prontuario->consulta && $prontuario->consulta->receitas->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-prescription me-2"></i>
                            Receitas Prescritas
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            @foreach ($prontuario->consulta->receitas as $receita)
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div>
                                        <div class="fw-bold">{{ $receita->medicamento }}</div>
                                        <small class="text-muted">
                                            {{ $receita->dosagem }} - {{ $receita->frequencia }}
                                            @if ($receita->duracao)
                                                - {{ $receita->duracao }}
                                            @endif
                                        </small>
                                        @if ($receita->observacoes)
                                            <br><small class="text-info">{{ $receita->observacoes }}</small>
                                        @endif
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
            @endif
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
                                {{ substr($prontuario->paciente->nome, 0, 1) }}
                            </div>
                        </div>
                        <div>
                            <div class="fw-bold">{{ $prontuario->paciente->nome }}</div>
                            <small class="text-muted">{{ $prontuario->paciente->cpf }}</small>
                        </div>
                    </div>

                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between px-0">
                            <span>Idade:</span>
                            <span>{{ $prontuario->paciente->data_nascimento->age }} anos</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between px-0">
                            <span>Sexo:</span>
                            <span>{{ $prontuario->paciente->sexo == 'M' ? 'Masculino' : 'Feminino' }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between px-0">
                            <span>Cartão SUS:</span>
                            <span class="small">{{ $prontuario->paciente->cartao_sus }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between px-0">
                            <span>Telefone:</span>
                            <span>{{ $prontuario->paciente->telefone ?: '-' }}</span>
                        </div>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('pacientes.show', $prontuario->paciente) }}"
                            class="btn btn-outline-info btn-sm w-100">
                            <i class="fas fa-user me-2"></i>
                            Ver Perfil Completo
                        </a>
                    </div>
                </div>
            </div>

            <!-- Histórico de Prontuários -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i>
                        Histórico de Prontuários
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        $prontuariosAnteriores = $prontuario->paciente
                            ->prontuarios()
                            ->where('id', '!=', $prontuario->id)
                            ->orderBy('created_at', 'desc')
                            ->limit(5)
                            ->get();
                    @endphp

                    @if ($prontuariosAnteriores->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach ($prontuariosAnteriores as $prontuarioAnterior)
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div>
                                        <div class="fw-bold">{{ $prontuarioAnterior->created_at->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $prontuarioAnterior->medico->nome }}</small>
                                        @if ($prontuarioAnterior->diagnostico)
                                            <br><small class="text-truncate" style="max-width: 200px; display: block;">
                                                {{ Str::limit($prontuarioAnterior->diagnostico, 50) }}
                                            </small>
                                        @endif
                                    </div>
                                    <div>
                                        <a href="{{ route('prontuarios.show', $prontuarioAnterior) }}"
                                            class="btn btn-outline-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if ($prontuario->paciente->prontuarios->count() > 6)
                            <div class="mt-3 text-center">
                                <a href="{{ route('pacientes.show', $prontuario->paciente) }}"
                                    class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-ellipsis-h me-2"></i>
                                    Ver Todos os Prontuários
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center text-muted">
                            <i class="fas fa-file-medical fa-2x mb-2"></i>
                            <p class="mb-0">Primeiro prontuário do paciente</p>
                        </div>
                    @endif
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
                        <a href="{{ route('prontuarios.edit', $prontuario) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>
                            Editar Prontuário
                        </a>
                        <button class="btn btn-outline-info" onclick="imprimirProntuario()">
                            <i class="fas fa-print me-2"></i>
                            Imprimir
                        </button>
                        @if ($prontuario->consulta)
                            <a href="{{ route('consultas.show', $prontuario->consulta) }}"
                                class="btn btn-outline-success">
                                <i class="fas fa-calendar-check me-2"></i>
                                Ver Consulta
                            </a>
                            @if ($prontuario->consulta->status == 'em_andamento')
                                <a href="{{ route('receitas.create', ['consulta_id' => $prontuario->consulta->id]) }}"
                                    class="btn btn-outline-warning">
                                    <i class="fas fa-prescription me-2"></i>
                                    Prescrever Receita
                                </a>
                            @endif
                        @endif
                        <a href="{{ route('pacientes.show', $prontuario->paciente) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-user me-2"></i>
                            Perfil do Paciente
                        </a>
                    </div>
                </div>
            </div>

            <!-- Informações do Sistema -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Informações do Sistema
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between px-0">
                            <span>Criado em:</span>
                            <span class="small">{{ $prontuario->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between px-0">
                            <span>Última atualização:</span>
                            <span class="small">{{ $prontuario->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between px-0">
                            <span>ID do Prontuário:</span>
                            <span class="badge bg-secondary">#{{ $prontuario->id }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function imprimirProntuario() {
            // Abrir página de impressão do prontuário
            window.open(`/prontuarios/{{ $prontuario->id }}/imprimir`, '_blank');
        }
    </script>
@endsection

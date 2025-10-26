@extends('layouts.modern')

@section('title', 'Detalhes do Paciente')

@section('breadcrumb')
    <i class="fas fa-home"></i>
    <span>Dashboard</span>
    <i class="fas fa-chevron-right"></i>
    <a href="{{ route('pacientes.index') }}">Pacientes</a>
    <i class="fas fa-chevron-right"></i>
    <span>{{ $paciente->nome }}</span>
@endsection

@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">
                    <i class="fas fa-user me-2"></i>
                    {{ $paciente->nome }}
                </h1>
                <p class="page-subtitle">Informações detalhadas do paciente</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('pacientes.edit', $paciente) }}" class="btn btn-warning">
                    <i class="fas fa-edit me-2"></i>
                    Editar
                </a>
                <a href="{{ route('pacientes.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Voltar
                </a>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Informações Principais -->
        <div class="col-lg-8">
            <!-- Dados Pessoais -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-user me-2"></i>
                        Dados Pessoais
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Nome Completo</label>
                            <p class="mb-0 fw-semibold">{{ $paciente->nome }}</p>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted">CPF</label>
                            <p class="mb-0 font-monospace">{{ $paciente->cpf_formatado }}</p>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted">Cartão SUS</label>
                            <p class="mb-0 font-monospace">{{ $paciente->cartao_sus }}</p>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted">Data de Nascimento</label>
                            <p class="mb-0">{{ $paciente->data_nascimento->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted">Idade</label>
                            <p class="mb-0">{{ $paciente->idade }} anos</p>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted">Sexo</label>
                            <p class="mb-0">{{ $paciente->sexo == 'M' ? 'Masculino' : 'Feminino' }}</p>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted">Status</label>
                            @if ($paciente->ativo)
                                <span class="status-badge status-active">Ativo</span>
                            @else
                                <span class="status-badge status-cancelled">Inativo</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contato -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-phone me-2"></i>
                        Informações de Contato
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Telefone</label>
                            <p class="mb-0">
                                @if ($paciente->telefone)
                                    <a href="tel:{{ $paciente->telefone }}" class="text-decoration-none">
                                        <i class="fas fa-phone me-1"></i>
                                        {{ $paciente->telefone }}
                                    </a>
                                @else
                                    <span class="text-muted">Não informado</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">E-mail</label>
                            <p class="mb-0">
                                @if ($paciente->email)
                                    <a href="mailto:{{ $paciente->email }}" class="text-decoration-none">
                                        <i class="fas fa-envelope me-1"></i>
                                        {{ $paciente->email }}
                                    </a>
                                @else
                                    <span class="text-muted">Não informado</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Endereço -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        Endereço
                    </h5>
                </div>
                <div class="card-body">
                    @if ($paciente->endereco)
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label text-muted">CEP</label>
                                <p class="mb-0 font-monospace">{{ $paciente->cep }}</p>
                            </div>
                            <div class="col-md-9">
                                <label class="form-label text-muted">Endereço</label>
                                <p class="mb-0">{{ $paciente->endereco }}</p>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-muted">Bairro</label>
                                <p class="mb-0">{{ $paciente->bairro ?? 'Não informado' }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted">Cidade</label>
                                <p class="mb-0">{{ $paciente->cidade ?? 'Não informado' }}</p>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label text-muted">UF</label>
                                <p class="mb-0">{{ $paciente->uf ?? '--' }}</p>
                            </div>
                        </div>
                    @else
                        <p class="text-muted mb-0">Endereço não informado</p>
                    @endif
                </div>
            </div>

            <!-- Histórico Médico -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-file-medical me-2"></i>
                        Histórico Médico Recente
                    </h5>
                </div>
                <div class="card-body">
                    @if ($paciente->consultas->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Data</th>
                                        <th>Médico</th>
                                        <th>Tipo</th>
                                        <th>Status</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($paciente->consultas->take(5) as $consulta)
                                        <tr>
                                            <td>{{ $consulta->data_hora->format('d/m/Y H:i') }}</td>
                                            <td>{{ $consulta->medico->nome ?? 'N/A' }}</td>
                                            <td>{{ $consulta->tipo ?? 'Consulta' }}</td>
                                            <td>
                                                @switch($consulta->status)
                                                    @case('confirmada')
                                                        <span class="status-badge status-active">Confirmada</span>
                                                    @break

                                                    @case('concluida')
                                                        <span class="status-badge status-completed">Concluída</span>
                                                    @break

                                                    @case('cancelada')
                                                        <span class="status-badge status-cancelled">Cancelada</span>
                                                    @break

                                                    @default
                                                        <span class="status-badge status-pending">Agendada</span>
                                                @endswitch
                                            </td>
                                            <td>
                                                <a href="{{ route('consultas.show', $consulta) }}"
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if ($paciente->consultas->count() > 5)
                            <div class="text-center mt-3">
                                <a href="{{ route('consultas.index', ['paciente_id' => $paciente->id]) }}"
                                    class="btn btn-outline-primary btn-sm">
                                    Ver todas as consultas
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-calendar-times fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">Nenhuma consulta registrada</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
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
                        <a href="{{ route('consultas.create', ['paciente_id' => $paciente->id]) }}"
                            class="btn btn-primary">
                            <i class="fas fa-calendar-plus me-2"></i>
                            Nova Consulta
                        </a>
                        <a href="{{ route('prontuarios.index', ['paciente_id' => $paciente->id]) }}"
                            class="btn btn-info">
                            <i class="fas fa-file-medical me-2"></i>
                            Ver Prontuário
                        </a>
                        <a href="{{ route('exames.create', ['paciente_id' => $paciente->id]) }}" class="btn btn-warning">
                            <i class="fas fa-vial me-2"></i>
                            Solicitar Exame
                        </a>
                        <a href="{{ route('receitas.create', ['paciente_id' => $paciente->id]) }}"
                            class="btn btn-success">
                            <i class="fas fa-prescription me-2"></i>
                            Nova Receita
                        </a>
                    </div>
                </div>
            </div>

            <!-- Estatísticas -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>
                        Estatísticas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3 text-center">
                        <div class="col-6">
                            <div class="p-2">
                                <i class="fas fa-calendar-check fa-2x text-primary mb-2"></i>
                                <h4 class="mb-0">{{ $paciente->consultas->count() }}</h4>
                                <small class="text-muted">Consultas</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2">
                                <i class="fas fa-vial fa-2x text-warning mb-2"></i>
                                <h4 class="mb-0">{{ $paciente->exames->count() ?? 0 }}</h4>
                                <small class="text-muted">Exames</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2">
                                <i class="fas fa-prescription fa-2x text-success mb-2"></i>
                                <h4 class="mb-0">{{ $paciente->receitas->count() ?? 0 }}</h4>
                                <small class="text-muted">Receitas</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2">
                                <i class="fas fa-file-medical fa-2x text-info mb-2"></i>
                                <h4 class="mb-0">{{ $paciente->prontuarios->count() ?? 0 }}</h4>
                                <small class="text-muted">Prontuários</small>
                            </div>
                        </div>
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
                    <div class="small text-muted">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Cadastrado em:</span>
                            <span>{{ $paciente->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Última atualização:</span>
                            <span>{{ $paciente->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>ID do sistema:</span>
                            <span class="font-monospace">#{{ $paciente->id }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .font-monospace {
            font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        }
    </style>
@endsection

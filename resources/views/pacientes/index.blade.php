@extends('layouts.modern')

@section('title', 'Pacientes')

@section('breadcrumb')
    <i class="fas fa-home"></i>
    <span>Dashboard</span>
    <i class="fas fa-chevron-right"></i>
    <span>Pacientes</span>
@endsection

@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">
                    <i class="fas fa-users me-2"></i>
                    Gestão de Pacientes
                </h1>
                <p class="page-subtitle">Gerencie o cadastro de pacientes do sistema</p>
            </div>
            <div>
                <a href="{{ route('pacientes.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    Novo Paciente
                </a>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('pacientes.index') }}">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Buscar</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" name="search" class="form-control" placeholder="Nome, CPF ou Cartão SUS"
                                value="{{ request('search') }}" autofocus>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Todos</option>
                            <option value="ativo" {{ request('status') == 'ativo' ? 'selected' : '' }}>Ativo</option>
                            <option value="inativo" {{ request('status') == 'inativo' ? 'selected' : '' }}>Inativo</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-1"></i>
                                Buscar
                            </button>
                            <a href="{{ route('pacientes.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i>
                                Limpar
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de Pacientes -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-list me-2"></i>
                Lista de Pacientes
                <span class="badge bg-primary ms-2">{{ $pacientes->total() }}</span>
            </h5>
        </div>
        <div class="card-body p-0">
            @if ($pacientes->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>CPF</th>
                                <th>Cartão SUS</th>
                                <th>Telefone</th>
                                <th>Data Nascimento</th>
                                <th>Status</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pacientes as $paciente)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div
                                                class="avatar-sm bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center me-3">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $paciente->nome }}</h6>
                                                <small class="text-muted">{{ $paciente->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="font-monospace">{{ $paciente->cpf_formatado }}</span>
                                    </td>
                                    <td>
                                        <span class="font-monospace">{{ $paciente->cartao_sus }}</span>
                                    </td>
                                    <td>{{ $paciente->telefone }}</td>
                                    <td>{{ $paciente->data_nascimento->format('d/m/Y') }}</td>
                                    <td>
                                        @if ($paciente->ativo)
                                            <span class="status-badge status-active">Ativo</span>
                                        @else
                                            <span class="status-badge status-cancelled">Inativo</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('pacientes.show', $paciente) }}"
                                                class="btn btn-outline-primary" title="Visualizar">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('pacientes.edit', $paciente) }}"
                                                class="btn btn-outline-warning" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('prontuarios.index', ['paciente_id' => $paciente->id]) }}"
                                                class="btn btn-outline-info" title="Prontuário">
                                                <i class="fas fa-file-medical"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginação -->
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="small text-muted">
                            Mostrando {{ $pacientes->firstItem() }} a {{ $pacientes->lastItem() }}
                            de {{ $pacientes->total() }} registros
                        </div>
                        <div>
                            {{ $pacientes->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Nenhum paciente encontrado</h5>
                    <p class="text-muted">Não há pacientes cadastrados ou que correspondam aos filtros aplicados.</p>
                    <a href="{{ route('pacientes.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        Cadastrar Primeiro Paciente
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row g-4 mt-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-users fa-2x text-primary mb-2"></i>
                    <h4 class="mb-1">{{ $pacientes->total() }}</h4>
                    <small class="text-muted">Total de Pacientes</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-user-check fa-2x text-success mb-2"></i>
                    <h4 class="mb-1">{{ $pacientes->where('ativo', true)->count() }}</h4>
                    <small class="text-muted">Pacientes Ativos</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-calendar-day fa-2x text-info mb-2"></i>
                    <h4 class="mb-1">{{ $pacientes->where('created_at', '>=', today())->count() }}</h4>
                    <small class="text-muted">Cadastros Hoje</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-calendar-week fa-2x text-warning mb-2"></i>
                    <h4 class="mb-1">{{ $pacientes->where('created_at', '>=', now()->startOfWeek())->count() }}</h4>
                    <small class="text-muted">Esta Semana</small>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .avatar-sm {
            width: 32px;
            height: 32px;
            font-size: 0.875rem;
        }

        .font-monospace {
            font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        }

        .btn-group-sm .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
    </style>
@endsection

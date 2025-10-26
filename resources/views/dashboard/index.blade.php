@extends('layouts.modern')

@section('title', 'Dashboard')

@section('breadcrumb')
    <i class="fas fa-home"></i>
    <span>Dashboard</span>
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">Dashboard</h1>
        <p class="page-subtitle">Bem-vindo ao sistema de prontuário eletrônico</p>
    </div>

    <!-- Quick Stats -->
    <div class="row g-4 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(8, 145, 178, 0.1); color: var(--primary-color);">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-value">1,234</div>
                <div class="stat-label">Total de Pacientes</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i> +12% este mês
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(5, 150, 105, 0.1); color: var(--success-color);">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-value">156</div>
                <div class="stat-label">Consultas Hoje</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i> +8% esta semana
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(217, 119, 6, 0.1); color: var(--warning-color);">
                    <i class="fas fa-vial"></i>
                </div>
                <div class="stat-value">89</div>
                <div class="stat-label">Exames Pendentes</div>
                <div class="stat-change negative">
                    <i class="fas fa-arrow-down"></i> -3% esta semana
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(37, 99, 235, 0.1); color: var(--info-color);">
                    <i class="fas fa-file-medical"></i>
                </div>
                <div class="stat-value">2,456</div>
                <div class="stat-label">Prontuários</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i> +15% este mês
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Content Area -->
        <div class="col-lg-8">
            <!-- Recent Activities -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-clock me-2"></i>
                        Atividades Recentes
                    </h5>
                    <a href="#" class="btn btn-outline-primary btn-sm">Ver Todas</a>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item border-0 px-0">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div
                                        class="avatar-sm bg-success-subtle text-success rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="fas fa-user-plus"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">Novo paciente cadastrado</h6>
                                    <p class="text-muted mb-0 small">Maria Silva foi cadastrada no sistema</p>
                                </div>
                                <small class="text-muted">há 5 min</small>
                            </div>
                        </div>

                        <div class="list-group-item border-0 px-0">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div
                                        class="avatar-sm bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="fas fa-calendar-check"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">Consulta realizada</h6>
                                    <p class="text-muted mb-0 small">Dr. João completou consulta com paciente #1234</p>
                                </div>
                                <small class="text-muted">há 12 min</small>
                            </div>
                        </div>

                        <div class="list-group-item border-0 px-0">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div
                                        class="avatar-sm bg-warning-subtle text-warning rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="fas fa-vial"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">Exame solicitado</h6>
                                    <p class="text-muted mb-0 small">Hemograma completo solicitado para João Santos</p>
                                </div>
                                <small class="text-muted">há 25 min</small>
                            </div>
                        </div>

                        <div class="list-group-item border-0 px-0">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div
                                        class="avatar-sm bg-info-subtle text-info rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="fas fa-prescription"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">Receita emitida</h6>
                                    <p class="text-muted mb-0 small">Receita médica gerada para Ana Costa</p>
                                </div>
                                <small class="text-muted">há 1 hora</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Consultas Chart -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-line me-2"></i>
                        Consultas nos Últimos 7 Dias
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="consultasChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt me-2"></i>
                        Ações Rápidas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('pacientes.create') }}" class="btn btn-primary">
                            <i class="fas fa-user-plus me-2"></i>
                            Novo Paciente
                        </a>
                        <a href="{{ route('consultas.create') }}" class="btn btn-success">
                            <i class="fas fa-calendar-plus me-2"></i>
                            Agendar Consulta
                        </a>
                        <a href="{{ route('exames.create') }}" class="btn btn-warning">
                            <i class="fas fa-vial me-2"></i>
                            Solicitar Exame
                        </a>
                        <a href="{{ route('receitas.create') }}" class="btn btn-info">
                            <i class="fas fa-prescription me-2"></i>
                            Nova Receita
                        </a>
                    </div>
                </div>
            </div>

            <!-- Today's Schedule -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-day me-2"></i>
                        Agenda de Hoje
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item border-0 px-0 py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">08:00</h6>
                                    <p class="mb-0 small text-muted">Maria Silva - Consulta</p>
                                </div>
                                <span class="status-badge status-active">Confirmada</span>
                            </div>
                        </div>

                        <div class="list-group-item border-0 px-0 py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">09:30</h6>
                                    <p class="mb-0 small text-muted">João Santos - Retorno</p>
                                </div>
                                <span class="status-badge status-pending">Aguardando</span>
                            </div>
                        </div>

                        <div class="list-group-item border-0 px-0 py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">11:00</h6>
                                    <p class="mb-0 small text-muted">Ana Costa - Consulta</p>
                                </div>
                                <span class="status-badge status-completed">Concluída</span>
                            </div>
                        </div>

                        <div class="list-group-item border-0 px-0 py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">14:00</h6>
                                    <p class="mb-0 small text-muted">Pedro Lima - Emergência</p>
                                </div>
                                <span class="status-badge status-cancelled">Cancelada</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Status -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-server me-2"></i>
                        Status do Sistema
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3 text-center">
                        <div class="col-6">
                            <div class="p-2">
                                <i class="fas fa-database fa-2x text-success mb-2"></i>
                                <div class="small">Banco de Dados</div>
                                <div class="text-success fw-bold">Online</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2">
                                <i class="fas fa-cloud fa-2x text-success mb-2"></i>
                                <div class="small">Backup</div>
                                <div class="text-success fw-bold">Atualizado</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2">
                                <i class="fas fa-shield-alt fa-2x text-success mb-2"></i>
                                <div class="small">Segurança</div>
                                <div class="text-success fw-bold">Ativa</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2">
                                <i class="fas fa-users fa-2x text-primary mb-2"></i>
                                <div class="small">Usuários Online</div>
                                <div class="text-primary fw-bold">23</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Consultas Chart
        const ctx = document.getElementById('consultasChart').getContext('2d');
        const consultasChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
                datasets: [{
                    label: 'Consultas Realizadas',
                    data: [65, 78, 90, 81, 92, 45, 23],
                    borderColor: 'rgb(8, 145, 178)',
                    backgroundColor: 'rgba(8, 145, 178, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#e2e8f0'
                        }
                    },
                    x: {
                        grid: {
                            color: '#e2e8f0'
                        }
                    }
                }
            }
        });
    </script>
@endsection

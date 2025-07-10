<div>
    <!-- Filtros de período -->
    <div class="mb-4 d-flex justify-content-end">
        <div class="btn-group" role="group" aria-label="Filtros de período">
            <button type="button" class="btn {{ $period === 'today' ? 'btn-primary' : 'btn-outline-primary' }}"
                wire:click="setPeriod('today')">Hoje</button>
            <button type="button" class="btn {{ $period === 'week' ? 'btn-primary' : 'btn-outline-primary' }}"
                wire:click="setPeriod('week')">Esta Semana</button>
            <button type="button" class="btn {{ $period === 'month' ? 'btn-primary' : 'btn-outline-primary' }}"
                wire:click="setPeriod('month')">Este Mês</button>
        </div>
    </div>

    <!-- Cards de Estatísticas -->
    <div class="mb-4 row">
        <!-- Pacientes Cadastrados -->
        <div class="mb-4 col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 stat-card" x-data="{ hover: false }" @mouseenter="hover = true"
                @mouseleave="hover = false" :class="{ 'transform-up': hover }">
                <div class="card-body position-relative overflow-hidden p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase text-muted mb-2">Pacientes Cadastrados</h6>
                            <h2 class="mb-0 display-5 fw-bold text-primary" wire:loading.class="opacity-50">
                                {{ $totalPacientes }}
                            </h2>
                        </div>
                        <div class="icon-circle bg-primary bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-user-injured fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Consultas -->
        <div class="mb-4 col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 stat-card" x-data="{ hover: false }" @mouseenter="hover = true"
                @mouseleave="hover = false" :class="{ 'transform-up': hover }">
                <div class="card-body position-relative overflow-hidden p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase text-muted mb-2">
                                Consultas
                                <span class="badge bg-success rounded-pill">
                                    {{ $period === 'today' ? 'Hoje' : ($period === 'week' ? 'Semana' : 'Mês') }}
                                </span>
                            </h6>
                            <h2 class="mb-0 display-5 fw-bold text-success" wire:loading.class="opacity-50">
                                {{ $totalConsultas }}
                            </h2>
                        </div>
                        <div class="icon-circle bg-success bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-stethoscope fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Médicos Ativos -->
        <div class="mb-4 col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 stat-card" x-data="{ hover: false }" @mouseenter="hover = true"
                @mouseleave="hover = false" :class="{ 'transform-up': hover }">
                <div class="card-body position-relative overflow-hidden p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase text-muted mb-2">Médicos Ativos</h6>
                            <h2 class="mb-0 display-5 fw-bold text-info" wire:loading.class="opacity-50">
                                {{ $totalMedicos }}
                            </h2>
                        </div>
                        <div class="icon-circle bg-info bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-user-md fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Consultas Pendentes -->
        <div class="mb-4 col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 stat-card" x-data="{ hover: false }" @mouseenter="hover = true"
                @mouseleave="hover = false" :class="{ 'transform-up': hover }">
                <div class="card-body position-relative overflow-hidden p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase text-muted mb-2">Consultas Pendentes</h6>
                            <h2 class="mb-0 display-5 fw-bold text-danger" wire:loading.class="opacity-50">
                                {{ $consultasPendentes }}
                            </h2>
                        </div>
                        <div class="icon-circle bg-danger bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-calendar-check fa-2x text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Indicador de carregamento -->
    <div wire:loading class="text-center mb-4">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Carregando...</span>
        </div>
        <span class="ms-2 text-muted">Atualizando dados...</span>
    </div>

    <!-- Refresh automático -->
    <div wire:poll.{{ $refreshInterval }}ms="loadStats"></div>
</div>

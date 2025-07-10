<div>
    <div class="card card-chart shadow border-0 mb-4" x-data="{ hover: false }" @mouseenter="hover = true"
        @mouseleave="hover = false" :class="{ 'transform-up': hover }">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                {{ $showEspecialidades ? 'Consultas por Especialidade' : 'Atendimentos Mensais' }}
            </h6>
            <div class="btn-group">
                <!-- Botões para alternar o tipo de gráfico -->
                <button type="button"
                    class="btn {{ $chartType === 'bar' ? 'btn-primary' : 'btn-outline-primary' }} btn-sm"
                    wire:click="toggleChartType('bar')">
                    <i class="fas fa-chart-bar"></i> Barras
                </button>
                <button type="button"
                    class="btn {{ $chartType === 'line' ? 'btn-primary' : 'btn-outline-primary' }} btn-sm"
                    wire:click="toggleChartType('line')">
                    <i class="fas fa-chart-line"></i> Linear
                </button>
                <button type="button"
                    class="btn {{ $chartType === 'pie' ? 'btn-primary' : 'btn-outline-primary' }} btn-sm"
                    wire:click="toggleChartType('pie')">
                    <i class="fas fa-chart-pie"></i> Pizza
                </button>
            </div>
        </div>
        <div class="card-body">
            <!-- Filtros adicionais -->
            <div class="mb-4">
                <div class="row">
                    <div class="col-md-6">
                        <div class="btn-group btn-group-sm">
                            <button type="button"
                                class="btn {{ !$showEspecialidades ? 'btn-success' : 'btn-outline-success' }}"
                                wire:click="toggleDataType(false)">
                                Consultas por Mês
                            </button>
                            <button type="button"
                                class="btn {{ $showEspecialidades ? 'btn-success' : 'btn-outline-success' }}"
                                wire:click="toggleDataType(true)">
                                Por Especialidade
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <div class="d-flex justify-content-end gap-2">
                            @if (!$showEspecialidades)
                                <div class="btn-group btn-group-sm" role="group" aria-label="Período">
                                    <button type="button"
                                        class="btn {{ $timeRange === 3 ? 'btn-info' : 'btn-outline-info' }}"
                                        wire:click="setTimeRange(3)">
                                        3 Meses
                                    </button>
                                    <button type="button"
                                        class="btn {{ $timeRange === 6 ? 'btn-info' : 'btn-outline-info' }}"
                                        wire:click="setTimeRange(6)">
                                        6 Meses
                                    </button>
                                    <button type="button"
                                        class="btn {{ $timeRange === 12 ? 'btn-info' : 'btn-outline-info' }}"
                                        wire:click="setTimeRange(12)">
                                        12 Meses
                                    </button>
                                </div>
                            @endif

                            <button class="btn btn-sm {{ $autoRefresh ? 'btn-success' : 'btn-outline-secondary' }}"
                                wire:click="$toggle('autoRefresh')" title="Atualização automática">
                                <i class="fas fa-sync-alt"></i>
                                {{ $autoRefresh ? 'Auto' : 'Manual' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Área do gráfico -->
            <div class="chart-container" style="position: relative; height:300px;">
                <canvas id="consultasChart" wire:ignore></canvas>
            </div>

            <!-- Indicador de carregamento -->
            <div wire:loading class="text-center mt-3">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Carregando...</span>
                </div>
            </div>

            <!-- Botão para recarregar em caso de erro -->
            <div class="text-center mt-3">
                <button class="btn btn-sm btn-outline-primary" wire:click="loadChartData">
                    <i class="fas fa-sync-alt"></i> Recarregar dados
                </button>
            </div>

            <!-- Auto refresh -->
            @if ($autoRefresh)
                <div wire:poll.{{ $refreshInterval }}ms="loadChartData" class="text-end mt-2">
                    <small class="text-muted">
                        <i class="fas fa-sync-alt fa-spin"></i>
                        Atualização automática a cada {{ $refreshInterval / 1000 }} segundos
                    </small>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('livewire:initialized', () => {
                let chart = null;
                let errorTimeout = null;

                function initChart() {
                    const ctx = document.getElementById('consultasChart').getContext('2d');
                    const chartData = @this.chartData;
                    const chartType = @this.chartType;

                    // Destruir gráfico anterior se existir
                    if (chart) {
                        chart.destroy();
                    }

                    // Configurações comuns
                    const config = {
                        type: chartType,
                        data: chartData,
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            animation: {
                                duration: 1000,
                                easing: 'easeOutQuart'
                            }
                        }
                    };

                    // Configurações específicas para cada tipo de gráfico
                    if (chartType === 'bar' || chartType === 'line') {
                        config.options.scales = {
                            y: {
                                beginAtZero: true
                            }
                        };
                    } else if (chartType === 'pie') {
                        config.options.plugins = {
                            legend: {
                                position: 'bottom'
                            }
                        };
                    }

                    // Criar gráfico
                    chart = new Chart(ctx, config);
                }

                // Inicializar gráfico
                initChart();

                // Atualizar quando os dados mudarem
                Livewire.on('chartDataUpdated', () => {
                    initChart();
                });

                // Atualizar quando o tipo de gráfico mudar
                Livewire.on('chartTypeChanged', () => {
                    initChart();
                });

                // Atualizar quando o tipo de dados mudar
                Livewire.on('dataTypeChanged', () => {
                    initChart();
                });

                // Versão compatível com Livewire 3
                document.addEventListener('chartDataUpdated', () => {
                    initChart();
                });
                document.addEventListener('chartTypeChanged', () => {
                    initChart();
                });
                document.addEventListener('dataTypeChanged', () => {
                    initChart();
                });

                // Tratar erros - para Livewire 2
                Livewire.on('chartError', (message) => {
                    handleChartError(message);
                });

                // Tratar erros - para Livewire 3
                document.addEventListener('chartError', (e) => {
                    handleChartError(e.detail);
                });

                function handleChartError(message) {
                    // Mostrar mensagem de erro
                    const errorContainer = document.getElementById('chart-error') || document.createElement(
                        'div');
                    errorContainer.id = 'chart-error';
                    errorContainer.className = 'alert alert-danger alert-dismissible fade show mt-3';
                    errorContainer.innerHTML = `
                        <strong>Erro!</strong> ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    `;

                    // Adicionar ao DOM se ainda não estiver
                    const chartContainer = document.querySelector('.chart-container');
                    if (!document.getElementById('chart-error')) {
                        chartContainer.after(errorContainer);
                    }

                    // Auto-esconder após alguns segundos
                    if (errorTimeout) {
                        clearTimeout(errorTimeout);
                    }
                    errorTimeout = setTimeout(() => {
                        errorContainer.remove();
                    }, 8000);
                }
            });
        </script>
    @endpush
</div>

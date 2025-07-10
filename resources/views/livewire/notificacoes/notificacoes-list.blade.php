<div>
    <div class="dropdown-menu dropdown-menu-end dropdown-notifications p-0" aria-labelledby="notificacoesDropdown"
        style="{{ $mostrarDetalhes ? 'min-width: 400px;' : 'min-width: 320px;' }}">
        <!-- Cabeçalho -->
        <div class="dropdown-header bg-primary text-white p-3 d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-bell me-1"></i>
                Notificações <span class="badge bg-light text-dark">{{ $totalNaoLidas }}</span>
            </div>
            <div class="btn-group">
                <button class="btn btn-sm btn-primary {{ $mostrarTodasNotificacoes ? 'active' : '' }}"
                    wire:click="toggleMostrarTodas"
                    title="{{ $mostrarTodasNotificacoes ? 'Mostrar apenas não lidas' : 'Mostrar todas' }}">
                    <i class="fas {{ $mostrarTodasNotificacoes ? 'fa-filter' : 'fa-list' }}"></i>
                </button>
                <button class="btn btn-sm btn-primary" wire:click="marcarTodasComoLidas"
                    title="Marcar todas como lidas">
                    <i class="fas fa-check-double"></i>
                </button>
            </div>
        </div>

        <!-- Detalhes da notificação selecionada -->
        @if ($mostrarDetalhes && $notificacaoSelecionada)
            <div class="notification-details p-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h6 class="fw-bold mb-1">{{ $notificacaoSelecionada->titulo }}</h6>
                    <button class="btn btn-sm btn-link text-secondary" wire:click="fecharDetalhes">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="notification-content p-2 bg-light rounded mb-2">
                    <p class="mb-2">{{ $notificacaoSelecionada->mensagem }}</p>

                    @if ($notificacaoSelecionada->dados_extras)
                        <div class="notification-extras small">
                            @if (
                                $notificacaoSelecionada->tipo == 'nova_consulta' ||
                                    $notificacaoSelecionada->tipo == 'alteracao_consulta' ||
                                    $notificacaoSelecionada->tipo == 'confirmacao_consulta')
                                <a href="{{ route('consultas.show', $notificacaoSelecionada->dados_extras['consulta_id']) }}"
                                    class="btn btn-sm btn-primary mt-2">
                                    <i class="fas fa-calendar-check"></i> Ver detalhes da consulta
                                </a>

                                <a href="{{ route('pacientes.show', $notificacaoSelecionada->dados_extras['paciente_id']) }}"
                                    class="btn btn-sm btn-outline-secondary mt-2">
                                    <i class="fas fa-user"></i> Ver paciente
                                </a>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="notification-footer text-muted small">
                    <div class="d-flex justify-content-between">
                        <div>
                            @if ($notificacaoSelecionada->lida)
                                <i class="fas fa-check-double me-1"></i> Lida
                                {{ $notificacaoSelecionada->lida_em->diffForHumans() }}
                            @else
                                <i class="fas fa-envelope me-1"></i> Não lida
                            @endif
                        </div>
                        <div>
                            <i class="far fa-clock me-1"></i>
                            {{ $notificacaoSelecionada->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Lista de notificações -->
            <div class="notification-list" style="max-height: 400px; overflow-y: auto;">
                @if ($notificacoes->count() > 0)
                    @foreach ($notificacoes as $notificacao)
                        <a wire:click.prevent="verDetalhes({{ $notificacao->id }})" href="#"
                            class="dropdown-item notification-item d-flex align-items-center p-3 {{ !$notificacao->lida ? 'bg-light' : '' }} border-bottom">
                            <div class="notification-icon me-3">
                                @switch($notificacao->tipo)
                                    @case('nova_consulta')
                                        <i class="fas fa-calendar-plus text-success"></i>
                                    @break

                                    @case('alteracao_consulta')
                                        <i class="fas fa-calendar-alt text-warning"></i>
                                    @break

                                    @case('cancelamento_consulta')
                                        <i class="fas fa-calendar-times text-danger"></i>
                                    @break

                                    @case('confirmacao_consulta')
                                        <i class="fas fa-calendar-check text-primary"></i>
                                    @break

                                    @default
                                        <i class="fas fa-bell text-secondary"></i>
                                @endswitch
                            </div>
                            <div class="notification-content flex-grow-1">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $notificacao->titulo }}</h6>
                                    <small class="text-muted">{{ $notificacao->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-1 text-truncate">{{ $notificacao->mensagem }}</p>
                            </div>
                            @if (!$notificacao->lida)
                                <div class="ms-2">
                                    <span class="badge rounded-pill bg-primary">Novo</span>
                                </div>
                            @endif
                        </a>
                    @endforeach
                @else
                    <div class="dropdown-item text-center py-4">
                        <i class="fas fa-bell-slash fa-2x text-muted mb-2"></i>
                        <p class="mb-0">Nenhuma notificação encontrada</p>
                    </div>
                @endif
            </div>

            <!-- Paginação -->
            @if ($notificacoes->count() > 0)
                <div class="dropdown-divider m-0"></div>
                <div class="p-2 d-flex justify-content-center">
                    {{ $notificacoes->links() }}
                </div>
            @endif
        @endif

        <!-- Rodapé -->
        <div class="dropdown-divider m-0"></div>
        <div class="dropdown-item text-center py-2">
            <a href="{{ route('notificacoes.index') }}" class="text-decoration-none">
                <i class="fas fa-cog me-1"></i> Gerenciar notificações
            </a>
        </div>
    </div>
</div>

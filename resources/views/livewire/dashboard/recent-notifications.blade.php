<div class="card mb-4">
    <div class="card-header bg-light">
        <i class="fas fa-bell me-2 text-warning"></i> Notificações Recentes
    </div>
    <ul class="list-group list-group-flush">
        @forelse($notificacoes as $notificacao)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span>{{ $notificacao->mensagem }}</span>
                <span class="badge bg-{{ $notificacao->lida ? 'secondary' : 'warning' }} ms-2">
                    {{ $notificacao->created_at->diffForHumans() }}
                </span>
            </li>
        @empty
            <li class="list-group-item text-muted">Nenhuma notificação recente.</li>
        @endforelse
    </ul>
</div>

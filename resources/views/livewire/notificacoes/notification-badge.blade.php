<div class="dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="notificacoesDropdown" role="button"
        wire:click.prevent="toggleDropdown" aria-expanded="{{ $mostrarDropdown ? 'true' : 'false' }}">
        <i class="fas fa-bell"></i>
        @if ($contadorNotificacoes > 0)
            <span class="badge rounded-pill bg-danger">{{ $contadorNotificacoes }}</span>
        @endif
    </a>

    @if ($mostrarDropdown)
        <div class="dropdown-menu-container" style="position: absolute; z-index: 1000;">
            <livewire:notificacoes.notificacoes-list />
        </div>
    @endif
</div>

@push('scripts')
    <script>
        // Fechar dropdown quando clicar fora
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('notificacoesDropdown');
            if (dropdown && !dropdown.contains(event.target) && !event.target.closest('.dropdown-menu-container')) {
                @this.fecharDropdown();
            }
        });
    </script>
@endpush

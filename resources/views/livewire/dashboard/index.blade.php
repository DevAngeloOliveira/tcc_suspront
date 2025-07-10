<div>
    <div class="container-fluid">
        <div class="row g-3 mb-4">
            <div class="col-12">
                @livewire('dashboard.quick-links')
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-lg-8 mb-3 mb-lg-0">
                @livewire('dashboard.statistics-cards')
                <div class="mt-4">
                    @livewire('dashboard.consultas-chart')
                </div>
            </div>
            <div class="col-lg-4">
                @livewire('dashboard.recent-notifications')
            </div>
        </div>
    </div>
</div>

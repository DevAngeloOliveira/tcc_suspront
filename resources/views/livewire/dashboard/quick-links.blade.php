<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-lg-2">
        <a href="{{ route('pacientes.index') }}" class="card text-center shadow-sm h-100 text-decoration-none text-dark">
            <div class="card-body">
                <i class="fas fa-user-injured fa-2x mb-2 text-success"></i>
                <div>Pacientes</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <a href="{{ route('medicos.index') }}" class="card text-center shadow-sm h-100 text-decoration-none text-dark">
            <div class="card-body">
                <i class="fas fa-user-md fa-2x mb-2 text-primary"></i>
                <div>Médicos</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <a href="{{ route('atendentes.index') }}"
            class="card text-center shadow-sm h-100 text-decoration-none text-dark">
            <div class="card-body">
                <i class="fas fa-user-nurse fa-2x mb-2 text-info"></i>
                <div>Atendentes</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <a href="{{ route('consultas.index') }}"
            class="card text-center shadow-sm h-100 text-decoration-none text-dark">
            <div class="card-body">
                <i class="fas fa-stethoscope fa-2x mb-2 text-warning"></i>
                <div>Consultas</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <a href="{{ route('exames.index') }}" class="card text-center shadow-sm h-100 text-decoration-none text-dark">
            <div class="card-body">
                <i class="fas fa-microscope fa-2x mb-2 text-danger"></i>
                <div>Exames</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <a href="{{ route('receitas.index') }}" class="card text-center shadow-sm h-100 text-decoration-none text-dark">
            <div class="card-body">
                <i class="fas fa-prescription fa-2x mb-2 text-secondary"></i>
                <div>Receitas</div>
            </div>
        </a>
    </div>
</div>

@extends('layouts.modern')

@section('title', 'Nova Receita')

@section('breadcrumb')
    <i class="fas fa-home"></i>
    <span>Dashboard</span>
    <i class="fas fa-chevron-right"></i>
    <a href="{{ route('receitas.index') }}">Receitas</a>
    <i class="fas fa-chevron-right"></i>
    <span>Nova</span>
@endsection

@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">
                    <i class="fas fa-prescription-bottle-alt me-2"></i>
                    Nova Receita Médica
                </h1>
                <p class="page-subtitle">Prescreva medicamentos para o paciente</p>
            </div>
            <div>
                <a href="{{ route('receitas.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Voltar
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('receitas.store') }}" method="POST">
        @csrf

        <div class="row g-4">
            <div class="col-lg-8">
                <!-- Dados Básicos -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-user-check me-2"></i>
                            Dados da Prescrição
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="paciente_id" class="form-label">Paciente *</label>
                                <select class="form-select @error('paciente_id') is-invalid @enderror" id="paciente_id"
                                    name="paciente_id" required>
                                    <option value="">Selecione um paciente...</option>
                                    @foreach ($pacientes as $paciente)
                                        <option value="{{ $paciente->id }}"
                                            {{ old('paciente_id', request('paciente_id')) == $paciente->id ? 'selected' : '' }}
                                            data-cpf="{{ $paciente->cpf }}"
                                            data-nascimento="{{ $paciente->data_nascimento->format('d/m/Y') }}"
                                            data-idade="{{ $paciente->data_nascimento->age }}">
                                            {{ $paciente->nome }} - {{ $paciente->cpf }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('paciente_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="medico_id" class="form-label">Médico *</label>
                                <select class="form-select @error('medico_id') is-invalid @enderror" id="medico_id"
                                    name="medico_id" required>
                                    <option value="">Selecione um médico...</option>
                                    @foreach ($medicos as $medico)
                                        <option value="{{ $medico->id }}"
                                            {{ old('medico_id', request('medico_id')) == $medico->id ? 'selected' : '' }}
                                            data-especialidade="{{ $medico->especialidade }}"
                                            data-crm="{{ $medico->crm }}">
                                            {{ $medico->nome }} - {{ $medico->especialidade }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('medico_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            @if (request('consulta_id'))
                                <input type="hidden" name="consulta_id" value="{{ request('consulta_id') }}">
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>Receita vinculada à consulta #{{ request('consulta_id') }}</strong>
                                    </div>
                                </div>
                            @else
                                <div class="col-md-6">
                                    <label for="consulta_id" class="form-label">Consulta Relacionada</label>
                                    <select class="form-select @error('consulta_id') is-invalid @enderror" id="consulta_id"
                                        name="consulta_id">
                                        <option value="">Selecione uma consulta (opcional)...</option>
                                        @foreach ($consultas as $consulta)
                                            <option value="{{ $consulta->id }}"
                                                {{ old('consulta_id') == $consulta->id ? 'selected' : '' }}>
                                                Consulta #{{ $consulta->id }} - {{ $consulta->paciente->nome }}
                                                ({{ $consulta->data_consulta->format('d/m/Y') }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('consulta_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Medicamento -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-pills me-2"></i>
                            Informações do Medicamento
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label for="medicamento" class="form-label">Nome do Medicamento *</label>
                                <input type="text" class="form-control @error('medicamento') is-invalid @enderror"
                                    id="medicamento" name="medicamento" value="{{ old('medicamento') }}"
                                    placeholder="Ex: Dipirona Sódica" required autofocus>
                                @error('medicamento')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="forma_farmaceutica" class="form-label">Forma Farmacêutica</label>
                                <select class="form-select @error('forma_farmaceutica') is-invalid @enderror"
                                    id="forma_farmaceutica" name="forma_farmaceutica">
                                    <option value="">Selecione...</option>
                                    <option value="comprimido"
                                        {{ old('forma_farmaceutica') == 'comprimido' ? 'selected' : '' }}>Comprimido
                                    </option>
                                    <option value="capsula" {{ old('forma_farmaceutica') == 'capsula' ? 'selected' : '' }}>
                                        Cápsula</option>
                                    <option value="xarope" {{ old('forma_farmaceutica') == 'xarope' ? 'selected' : '' }}>
                                        Xarope</option>
                                    <option value="gotas" {{ old('forma_farmaceutica') == 'gotas' ? 'selected' : '' }}>
                                        Gotas</option>
                                    <option value="pomada" {{ old('forma_farmaceutica') == 'pomada' ? 'selected' : '' }}>
                                        Pomada</option>
                                    <option value="creme" {{ old('forma_farmaceutica') == 'creme' ? 'selected' : '' }}>
                                        Creme</option>
                                    <option value="gel" {{ old('forma_farmaceutica') == 'gel' ? 'selected' : '' }}>Gel
                                    </option>
                                    <option value="spray" {{ old('forma_farmaceutica') == 'spray' ? 'selected' : '' }}>
                                        Spray</option>
                                    <option value="injecao" {{ old('forma_farmaceutica') == 'injecao' ? 'selected' : '' }}>
                                        Injeção</option>
                                    <option value="supositorio"
                                        {{ old('forma_farmaceutica') == 'supositorio' ? 'selected' : '' }}>Supositório
                                    </option>
                                </select>
                                @error('forma_farmaceutica')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="principio_ativo" class="form-label">Princípio Ativo</label>
                                <input type="text" class="form-control @error('principio_ativo') is-invalid @enderror"
                                    id="principio_ativo" name="principio_ativo" value="{{ old('principio_ativo') }}"
                                    placeholder="Ex: Dipirona Sódica">
                                @error('principio_ativo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="concentracao" class="form-label">Concentração</label>
                                <input type="text" class="form-control @error('concentracao') is-invalid @enderror"
                                    id="concentracao" name="concentracao" value="{{ old('concentracao') }}"
                                    placeholder="Ex: 500mg">
                                @error('concentracao')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Posologia -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-clock me-2"></i>
                            Posologia
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="dosagem" class="form-label">Dosagem *</label>
                                <input type="text" class="form-control @error('dosagem') is-invalid @enderror"
                                    id="dosagem" name="dosagem" value="{{ old('dosagem') }}"
                                    placeholder="Ex: 1 comprimido" required>
                                @error('dosagem')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="frequencia" class="form-label">Frequência *</label>
                                <select class="form-select @error('frequencia') is-invalid @enderror" id="frequencia"
                                    name="frequencia" required>
                                    <option value="">Selecione...</option>
                                    <option value="1x ao dia" {{ old('frequencia') == '1x ao dia' ? 'selected' : '' }}>1x
                                        ao dia</option>
                                    <option value="2x ao dia" {{ old('frequencia') == '2x ao dia' ? 'selected' : '' }}>2x
                                        ao dia</option>
                                    <option value="3x ao dia" {{ old('frequencia') == '3x ao dia' ? 'selected' : '' }}>3x
                                        ao dia</option>
                                    <option value="4x ao dia" {{ old('frequencia') == '4x ao dia' ? 'selected' : '' }}>4x
                                        ao dia</option>
                                    <option value="6x ao dia" {{ old('frequencia') == '6x ao dia' ? 'selected' : '' }}>6x
                                        ao dia</option>
                                    <option value="8x ao dia" {{ old('frequencia') == '8x ao dia' ? 'selected' : '' }}>8x
                                        ao dia</option>
                                    <option value="12/12h" {{ old('frequencia') == '12/12h' ? 'selected' : '' }}>12/12h
                                    </option>
                                    <option value="8/8h" {{ old('frequencia') == '8/8h' ? 'selected' : '' }}>8/8h
                                    </option>
                                    <option value="6/6h" {{ old('frequencia') == '6/6h' ? 'selected' : '' }}>6/6h
                                    </option>
                                    <option value="4/4h" {{ old('frequencia') == '4/4h' ? 'selected' : '' }}>4/4h
                                    </option>
                                    <option value="SOS" {{ old('frequencia') == 'SOS' ? 'selected' : '' }}>SOS (se
                                        necessário)</option>
                                </select>
                                @error('frequencia')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="duracao" class="form-label">Duração do Tratamento</label>
                                <input type="text" class="form-control @error('duracao') is-invalid @enderror"
                                    id="duracao" name="duracao" value="{{ old('duracao') }}"
                                    placeholder="Ex: 7 dias">
                                @error('duracao')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="via_administracao" class="form-label">Via de Administração</label>
                                <select class="form-select @error('via_administracao') is-invalid @enderror"
                                    id="via_administracao" name="via_administracao">
                                    <option value="">Selecione...</option>
                                    <option value="oral" {{ old('via_administracao') == 'oral' ? 'selected' : '' }}>Via
                                        Oral</option>
                                    <option value="topica" {{ old('via_administracao') == 'topica' ? 'selected' : '' }}>
                                        Via Tópica</option>
                                    <option value="intramuscular"
                                        {{ old('via_administracao') == 'intramuscular' ? 'selected' : '' }}>Intramuscular
                                    </option>
                                    <option value="intravenosa"
                                        {{ old('via_administracao') == 'intravenosa' ? 'selected' : '' }}>Intravenosa
                                    </option>
                                    <option value="subcutanea"
                                        {{ old('via_administracao') == 'subcutanea' ? 'selected' : '' }}>Subcutânea
                                    </option>
                                    <option value="inalatoria"
                                        {{ old('via_administracao') == 'inalatoria' ? 'selected' : '' }}>Inalatória
                                    </option>
                                    <option value="ocular" {{ old('via_administracao') == 'ocular' ? 'selected' : '' }}>
                                        Ocular</option>
                                    <option value="nasal" {{ old('via_administracao') == 'nasal' ? 'selected' : '' }}>
                                        Nasal</option>
                                    <option value="retal" {{ old('via_administracao') == 'retal' ? 'selected' : '' }}>
                                        Retal</option>
                                </select>
                                @error('via_administracao')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="quantidade" class="form-label">Quantidade Total</label>
                                <input type="text" class="form-control @error('quantidade') is-invalid @enderror"
                                    id="quantidade" name="quantidade" value="{{ old('quantidade') }}"
                                    placeholder="Ex: 1 caixa (20 comprimidos)">
                                @error('quantidade')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Orientações -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Orientações e Observações
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="orientacoes" class="form-label">Orientações de Uso</label>
                            <textarea class="form-control @error('orientacoes') is-invalid @enderror" id="orientacoes" name="orientacoes"
                                rows="3" placeholder="Ex: Tomar com água, após as refeições...">{{ old('orientacoes') }}</textarea>
                            @error('orientacoes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="observacoes" class="form-label">Observações Adicionais</label>
                            <textarea class="form-control @error('observacoes') is-invalid @enderror" id="observacoes" name="observacoes"
                                rows="3" placeholder="Contraindicações, efeitos colaterais, interações...">{{ old('observacoes') }}</textarea>
                            @error('observacoes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="uso_continuo"
                                        name="uso_continuo" value="1" {{ old('uso_continuo') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="uso_continuo">
                                        Medicamento de uso contínuo
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="controle_especial"
                                        name="controle_especial" value="1"
                                        {{ old('controle_especial') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="controle_especial">
                                        Medicamento de controle especial
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Informações do Paciente -->
                <div class="card mb-4" id="paciente-info" style="display: none;">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-user me-2"></i>
                            Informações do Paciente
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between px-0">
                                <span>CPF:</span>
                                <span id="paciente-cpf" class="fw-bold">-</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between px-0">
                                <span>Nascimento:</span>
                                <span id="paciente-nascimento">-</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between px-0">
                                <span>Idade:</span>
                                <span id="paciente-idade" class="badge bg-info">-</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informações do Médico -->
                <div class="card mb-4" id="medico-info" style="display: none;">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-user-md me-2"></i>
                            Informações do Médico
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between px-0">
                                <span>Especialidade:</span>
                                <span id="medico-especialidade" class="fw-bold">-</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between px-0">
                                <span>CRM:</span>
                                <span id="medico-crm">-</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Calculadora de Dosagem -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-calculator me-2"></i>
                            Calculadora de Dosagem
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="calc-peso" class="form-label">Peso do paciente (kg)</label>
                            <input type="number" class="form-control" id="calc-peso" placeholder="70">
                        </div>
                        <div class="mb-3">
                            <label for="calc-dose-kg" class="form-label">Dose por kg (mg/kg)</label>
                            <input type="number" class="form-control" id="calc-dose-kg" placeholder="10">
                        </div>
                        <div class="mb-3">
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="calcularDose()">
                                <i class="fas fa-calculator me-2"></i>
                                Calcular Dose
                            </button>
                        </div>
                        <div id="resultado-calculo" class="text-muted">
                            <small>Preencha os campos para calcular</small>
                        </div>
                    </div>
                </div>

                <!-- Ações -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-prescription me-2"></i>
                                Prescrever Medicamento
                            </button>
                            <a href="{{ route('receitas.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>
                                Cancelar
                            </a>
                        </div>

                        <hr>

                        <div class="alert alert-warning alert-sm">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <small>
                                Verifique sempre a dosagem e contraindicações antes de prescrever.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Quando selecionar um paciente
            $('#paciente_id').change(function() {
                const selectedOption = $(this).find('option:selected');

                if (selectedOption.val()) {
                    $('#paciente-cpf').text(selectedOption.data('cpf'));
                    $('#paciente-nascimento').text(selectedOption.data('nascimento'));
                    $('#paciente-idade').text(selectedOption.data('idade') + ' anos');
                    $('#paciente-info').show();
                } else {
                    $('#paciente-info').hide();
                }
            });

            // Quando selecionar um médico
            $('#medico_id').change(function() {
                const selectedOption = $(this).find('option:selected');

                if (selectedOption.val()) {
                    $('#medico-especialidade').text(selectedOption.data('especialidade'));
                    $('#medico-crm').text(selectedOption.data('crm'));
                    $('#medico-info').show();
                } else {
                    $('#medico-info').hide();
                }
            });

            // Trigger para mostrar informações se já houver valores selecionados
            if ($('#paciente_id').val()) {
                $('#paciente_id').trigger('change');
            }

            if ($('#medico_id').val()) {
                $('#medico_id').trigger('change');
            }
        });

        function calcularDose() {
            const peso = parseFloat($('#calc-peso').val());
            const dosePorKg = parseFloat($('#calc-dose-kg').val());

            if (peso && dosePorKg) {
                const doseTotal = peso * dosePorKg;
                $('#resultado-calculo').html(`
            <div class="alert alert-info alert-sm mb-0">
                <strong>Dose total:</strong> ${doseTotal}mg<br>
                <small>Para ${peso}kg × ${dosePorKg}mg/kg</small>
            </div>
        `);
            } else {
                $('#resultado-calculo').html('<small class="text-muted">Preencha os campos para calcular</small>');
            }
        }
    </script>
@endsection

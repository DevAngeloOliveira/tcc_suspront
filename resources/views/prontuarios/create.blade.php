@extends('layouts.modern')

@section('title', 'Novo Prontuário')

@section('breadcrumb')
    <i class="fas fa-home"></i>
    <span>Dashboard</span>
    <i class="fas fa-chevron-right"></i>
    <a href="{{ route('prontuarios.index') }}">Prontuários</a>
    <i class="fas fa-chevron-right"></i>
    <span>Novo</span>
@endsection

@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">
                    <i class="fas fa-file-medical-alt me-2"></i>
                    Novo Prontuário Médico
                </h1>
                <p class="page-subtitle">Registre as informações médicas do atendimento</p>
            </div>
            <div>
                <a href="{{ route('prontuarios.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Voltar
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('prontuarios.store') }}" method="POST">
        @csrf

        <div class="row g-4">
            <div class="col-lg-8">
                <!-- Dados Básicos -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-user-check me-2"></i>
                            Dados Básicos
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
                                            data-cartao-sus="{{ $paciente->cartao_sus }}">
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
                                        <strong>Prontuário vinculado à consulta #{{ request('consulta_id') }}</strong>
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

                <!-- Anamnese -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-comments me-2"></i>
                            Anamnese
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="queixa_principal" class="form-label">Queixa Principal *</label>
                            <textarea class="form-control @error('queixa_principal') is-invalid @enderror" id="queixa_principal"
                                name="queixa_principal" rows="3" placeholder="Descreva a queixa principal do paciente..." required>{{ old('queixa_principal') }}</textarea>
                            @error('queixa_principal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="historia_doenca_atual" class="form-label">História da Doença Atual</label>
                            <textarea class="form-control @error('historia_doenca_atual') is-invalid @enderror" id="historia_doenca_atual"
                                name="historia_doenca_atual" rows="4"
                                placeholder="Descreva a evolução da doença, sintomas, duração, fatores agravantes e atenuantes...">{{ old('historia_doenca_atual') }}</textarea>
                            @error('historia_doenca_atual')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="historia_pregressa" class="form-label">História Pregressa</label>
                            <textarea class="form-control @error('historia_pregressa') is-invalid @enderror" id="historia_pregressa"
                                name="historia_pregressa" rows="3"
                                placeholder="Doenças anteriores, cirurgias, internações, alergias, medicamentos em uso...">{{ old('historia_pregressa') }}</textarea>
                            @error('historia_pregressa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="historia_familiar" class="form-label">História Familiar</label>
                            <textarea class="form-control @error('historia_familiar') is-invalid @enderror" id="historia_familiar"
                                name="historia_familiar" rows="3" placeholder="Histórico de doenças na família, fatores hereditários...">{{ old('historia_familiar') }}</textarea>
                            @error('historia_familiar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="historia_social" class="form-label">História Social</label>
                            <textarea class="form-control @error('historia_social') is-invalid @enderror" id="historia_social"
                                name="historia_social" rows="3"
                                placeholder="Hábitos de vida, profissão, tabagismo, etilismo, atividade física...">{{ old('historia_social') }}</textarea>
                            @error('historia_social')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Exame Físico -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-stethoscope me-2"></i>
                            Exame Físico
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3 mb-3">
                            <div class="col-md-3">
                                <label for="peso" class="form-label">Peso (kg)</label>
                                <input type="number" class="form-control @error('peso') is-invalid @enderror"
                                    id="peso" name="peso" value="{{ old('peso') }}" step="0.1"
                                    min="0" max="300" placeholder="70.5">
                                @error('peso')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label for="altura" class="form-label">Altura (cm)</label>
                                <input type="number" class="form-control @error('altura') is-invalid @enderror"
                                    id="altura" name="altura" value="{{ old('altura') }}" min="50"
                                    max="250" placeholder="170">
                                @error('altura')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label for="pressao_arterial" class="form-label">Pressão Arterial</label>
                                <input type="text"
                                    class="form-control @error('pressao_arterial') is-invalid @enderror"
                                    id="pressao_arterial" name="pressao_arterial" value="{{ old('pressao_arterial') }}"
                                    placeholder="120x80">
                                @error('pressao_arterial')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label for="temperatura" class="form-label">Temperatura (°C)</label>
                                <input type="number" class="form-control @error('temperatura') is-invalid @enderror"
                                    id="temperatura" name="temperatura" value="{{ old('temperatura') }}" step="0.1"
                                    min="30" max="45" placeholder="36.5">
                                @error('temperatura')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="exame_fisico" class="form-label">Exame Físico Detalhado</label>
                            <textarea class="form-control @error('exame_fisico') is-invalid @enderror" id="exame_fisico" name="exame_fisico"
                                rows="5" placeholder="Descreva os achados do exame físico por sistemas...">{{ old('exame_fisico') }}</textarea>
                            @error('exame_fisico')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Avaliação e Conduta -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-diagnoses me-2"></i>
                            Avaliação e Conduta Médica
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="diagnostico" class="form-label">Diagnóstico *</label>
                            <textarea class="form-control @error('diagnostico') is-invalid @enderror" id="diagnostico" name="diagnostico"
                                rows="3" placeholder="Diagnóstico principal e diagnósticos diferenciais..." required>{{ old('diagnostico') }}</textarea>
                            @error('diagnostico')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="plano_tratamento" class="form-label">Plano de Tratamento</label>
                            <textarea class="form-control @error('plano_tratamento') is-invalid @enderror" id="plano_tratamento"
                                name="plano_tratamento" rows="4"
                                placeholder="Medicações prescritas, orientações, retorno, exames solicitados...">{{ old('plano_tratamento') }}</textarea>
                            @error('plano_tratamento')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="observacoes" class="form-label">Observações Gerais</label>
                            <textarea class="form-control @error('observacoes') is-invalid @enderror" id="observacoes" name="observacoes"
                                rows="3" placeholder="Observações adicionais, evolução esperada, alertas...">{{ old('observacoes') }}</textarea>
                            @error('observacoes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                                <span>Cartão SUS:</span>
                                <span id="paciente-sus" class="small">-</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between px-0">
                                <span>Prontuários anteriores:</span>
                                <span id="paciente-prontuarios" class="badge bg-info">-</span>
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

                <!-- Calculadora IMC -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-calculator me-2"></i>
                            Calculadora IMC
                        </h5>
                    </div>
                    <div class="card-body">
                        <div id="imc-resultado" class="text-center">
                            <div class="text-muted">
                                <i class="fas fa-weight me-2"></i>
                                Preencha peso e altura
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ações -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i>
                                Salvar Prontuário
                            </button>
                            <a href="{{ route('prontuarios.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>
                                Cancelar
                            </a>
                        </div>

                        <hr>

                        <div class="alert alert-info alert-sm">
                            <i class="fas fa-shield-alt me-2"></i>
                            <small>
                                Todas as informações são protegidas pelo sigilo médico.
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
                    $('#paciente-sus').text(selectedOption.data('cartao-sus'));
                    $('#paciente-info').show();

                    // Buscar número de prontuários do paciente
                    buscarProntuariosPaciente(selectedOption.val());
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

            // Calcular IMC
            function calcularIMC() {
                const peso = parseFloat($('#peso').val());
                const altura = parseFloat($('#altura').val()) / 100; // converter cm para m

                if (peso && altura) {
                    const imc = peso / (altura * altura);
                    const imcFormatado = imc.toFixed(1);

                    let classificacao = '';
                    let corClass = '';

                    if (imc < 18.5) {
                        classificacao = 'Abaixo do peso';
                        corClass = 'text-warning';
                    } else if (imc < 25) {
                        classificacao = 'Peso normal';
                        corClass = 'text-success';
                    } else if (imc < 30) {
                        classificacao = 'Sobrepeso';
                        corClass = 'text-warning';
                    } else {
                        classificacao = 'Obesidade';
                        corClass = 'text-danger';
                    }

                    $('#imc-resultado').html(`
                <div class="fw-bold fs-4 ${corClass}">${imcFormatado}</div>
                <div class="small ${corClass}">${classificacao}</div>
            `);
                } else {
                    $('#imc-resultado').html(`
                <div class="text-muted">
                    <i class="fas fa-weight me-2"></i>
                    Preencha peso e altura
                </div>
            `);
                }
            }

            // Eventos para calcular IMC
            $('#peso, #altura').on('input', calcularIMC);

            function buscarProntuariosPaciente(pacienteId) {
                fetch(`/api/pacientes/${pacienteId}/prontuarios-count`)
                    .then(response => response.json())
                    .then(data => {
                        $('#paciente-prontuarios').text(data.count || 0);
                    })
                    .catch(error => {
                        $('#paciente-prontuarios').text('-');
                    });
            }

            // Trigger para mostrar informações se já houver valores selecionados
            if ($('#paciente_id').val()) {
                $('#paciente_id').trigger('change');
            }

            if ($('#medico_id').val()) {
                $('#medico_id').trigger('change');
            }
        });
    </script>
@endsection

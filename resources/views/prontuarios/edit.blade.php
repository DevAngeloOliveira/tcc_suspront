@extends('layouts.modern')

@section('title', 'Editar Prontuário')

@section('breadcrumb')
    <i class="fas fa-home"></i>
    <span>Dashboard</span>
    <i class="fas fa-chevron-right"></i>
    <a href="{{ route('prontuarios.index') }}">Prontuários</a>
    <i class="fas fa-chevron-right"></i>
    <a href="{{ route('prontuarios.show', $prontuario) }}">Prontuário #{{ $prontuario->id }}</a>
    <i class="fas fa-chevron-right"></i>
    <span>Editar</span>
@endsection

@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">
                    <i class="fas fa-file-medical-alt me-2"></i>
                    Editar Prontuário
                </h1>
                <p class="page-subtitle">Atualize as informações do prontuário #{{ $prontuario->id }}</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('prontuarios.show', $prontuario) }}" class="btn btn-outline-info">
                    <i class="fas fa-eye me-2"></i>
                    Visualizar
                </a>
                <a href="{{ route('prontuarios.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Voltar
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('prontuarios.update', $prontuario) }}" method="POST">
        @csrf
        @method('PUT')

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
                                    name="paciente_id" required disabled>
                                    @foreach ($pacientes as $paciente)
                                        <option value="{{ $paciente->id }}"
                                            {{ old('paciente_id', $prontuario->paciente_id) == $paciente->id ? 'selected' : '' }}
                                            data-cpf="{{ $paciente->cpf }}"
                                            data-nascimento="{{ $paciente->data_nascimento->format('d/m/Y') }}"
                                            data-cartao-sus="{{ $paciente->cartao_sus }}">
                                            {{ $paciente->nome }} - {{ $paciente->cpf }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="paciente_id" value="{{ $prontuario->paciente_id }}">
                                <small class="text-muted">O paciente não pode ser alterado após a criação do
                                    prontuário.</small>
                                @error('paciente_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="medico_id" class="form-label">Médico *</label>
                                <select class="form-select @error('medico_id') is-invalid @enderror" id="medico_id"
                                    name="medico_id" required>
                                    @foreach ($medicos as $medico)
                                        <option value="{{ $medico->id }}"
                                            {{ old('medico_id', $prontuario->medico_id) == $medico->id ? 'selected' : '' }}
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

                            @if ($prontuario->consulta)
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>Prontuário vinculado à consulta #{{ $prontuario->consulta->id }}</strong>
                                        <br><small>Data:
                                            {{ $prontuario->consulta->data_consulta->format('d/m/Y \à\s H:i') }}</small>
                                    </div>
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
                                name="queixa_principal" rows="3" placeholder="Descreva a queixa principal do paciente..." required>{{ old('queixa_principal', $prontuario->queixa_principal) }}</textarea>
                            @error('queixa_principal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="historia_doenca_atual" class="form-label">História da Doença Atual</label>
                            <textarea class="form-control @error('historia_doenca_atual') is-invalid @enderror" id="historia_doenca_atual"
                                name="historia_doenca_atual" rows="4"
                                placeholder="Descreva a evolução da doença, sintomas, duração, fatores agravantes e atenuantes...">{{ old('historia_doenca_atual', $prontuario->historia_doenca_atual) }}</textarea>
                            @error('historia_doenca_atual')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="historia_pregressa" class="form-label">História Pregressa</label>
                            <textarea class="form-control @error('historia_pregressa') is-invalid @enderror" id="historia_pregressa"
                                name="historia_pregressa" rows="3"
                                placeholder="Doenças anteriores, cirurgias, internações, alergias, medicamentos em uso...">{{ old('historia_pregressa', $prontuario->historia_pregressa) }}</textarea>
                            @error('historia_pregressa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="historia_familiar" class="form-label">História Familiar</label>
                            <textarea class="form-control @error('historia_familiar') is-invalid @enderror" id="historia_familiar"
                                name="historia_familiar" rows="3" placeholder="Histórico de doenças na família, fatores hereditários...">{{ old('historia_familiar', $prontuario->historia_familiar) }}</textarea>
                            @error('historia_familiar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="historia_social" class="form-label">História Social</label>
                            <textarea class="form-control @error('historia_social') is-invalid @enderror" id="historia_social"
                                name="historia_social" rows="3"
                                placeholder="Hábitos de vida, profissão, tabagismo, etilismo, atividade física...">{{ old('historia_social', $prontuario->historia_social) }}</textarea>
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
                                    id="peso" name="peso" value="{{ old('peso', $prontuario->peso) }}"
                                    step="0.1" min="0" max="300" placeholder="70.5">
                                @error('peso')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label for="altura" class="form-label">Altura (cm)</label>
                                <input type="number" class="form-control @error('altura') is-invalid @enderror"
                                    id="altura" name="altura" value="{{ old('altura', $prontuario->altura) }}"
                                    min="50" max="250" placeholder="170">
                                @error('altura')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label for="pressao_arterial" class="form-label">Pressão Arterial</label>
                                <input type="text" class="form-control @error('pressao_arterial') is-invalid @enderror"
                                    id="pressao_arterial" name="pressao_arterial"
                                    value="{{ old('pressao_arterial', $prontuario->pressao_arterial) }}"
                                    placeholder="120x80">
                                @error('pressao_arterial')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label for="temperatura" class="form-label">Temperatura (°C)</label>
                                <input type="number" class="form-control @error('temperatura') is-invalid @enderror"
                                    id="temperatura" name="temperatura"
                                    value="{{ old('temperatura', $prontuario->temperatura) }}" step="0.1"
                                    min="30" max="45" placeholder="36.5">
                                @error('temperatura')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="exame_fisico" class="form-label">Exame Físico Detalhado</label>
                            <textarea class="form-control @error('exame_fisico') is-invalid @enderror" id="exame_fisico" name="exame_fisico"
                                rows="5" placeholder="Descreva os achados do exame físico por sistemas...">{{ old('exame_fisico', $prontuario->exame_fisico) }}</textarea>
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
                                rows="3" placeholder="Diagnóstico principal e diagnósticos diferenciais..." required>{{ old('diagnostico', $prontuario->diagnostico) }}</textarea>
                            @error('diagnostico')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="plano_tratamento" class="form-label">Plano de Tratamento</label>
                            <textarea class="form-control @error('plano_tratamento') is-invalid @enderror" id="plano_tratamento"
                                name="plano_tratamento" rows="4"
                                placeholder="Medicações prescritas, orientações, retorno, exames solicitados...">{{ old('plano_tratamento', $prontuario->plano_tratamento) }}</textarea>
                            @error('plano_tratamento')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="observacoes" class="form-label">Observações Gerais</label>
                            <textarea class="form-control @error('observacoes') is-invalid @enderror" id="observacoes" name="observacoes"
                                rows="3" placeholder="Observações adicionais, evolução esperada, alertas...">{{ old('observacoes', $prontuario->observacoes) }}</textarea>
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
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-user me-2"></i>
                            Informações do Paciente
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar-lg me-3">
                                <div class="avatar-title rounded-circle bg-primary">
                                    {{ substr($prontuario->paciente->nome, 0, 1) }}
                                </div>
                            </div>
                            <div>
                                <div class="fw-bold">{{ $prontuario->paciente->nome }}</div>
                                <small class="text-muted">{{ $prontuario->paciente->cpf }}</small>
                            </div>
                        </div>

                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between px-0">
                                <span>Nascimento:</span>
                                <span>{{ $prontuario->paciente->data_nascimento->format('d/m/Y') }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between px-0">
                                <span>Idade:</span>
                                <span>{{ $prontuario->paciente->data_nascimento->age }} anos</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between px-0">
                                <span>Sexo:</span>
                                <span>{{ $prontuario->paciente->sexo == 'M' ? 'Masculino' : 'Feminino' }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between px-0">
                                <span>Cartão SUS:</span>
                                <span class="small">{{ $prontuario->paciente->cartao_sus }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informações do Médico -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-user-md me-2"></i>
                            Informações do Médico
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar-lg me-3">
                                <div class="avatar-title rounded-circle bg-success">
                                    {{ substr($prontuario->medico->nome, 0, 1) }}
                                </div>
                            </div>
                            <div>
                                <div class="fw-bold" id="medico-nome">{{ $prontuario->medico->nome }}</div>
                                <small class="text-muted"
                                    id="medico-especialidade">{{ $prontuario->medico->especialidade }}</small>
                            </div>
                        </div>

                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between px-0">
                                <span>CRM:</span>
                                <span id="medico-crm">{{ $prontuario->medico->crm }}</span>
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
                            @if ($prontuario->peso && $prontuario->altura)
                                @php
                                    $imc = $prontuario->peso / ($prontuario->altura / 100) ** 2;
                                    $imcFormatado = number_format($imc, 1);
                                    $classificacao = '';
                                    $corClass = '';

                                    if ($imc < 18.5) {
                                        $classificacao = 'Abaixo do peso';
                                        $corClass = 'text-warning';
                                    } elseif ($imc < 25) {
                                        $classificacao = 'Peso normal';
                                        $corClass = 'text-success';
                                    } elseif ($imc < 30) {
                                        $classificacao = 'Sobrepeso';
                                        $corClass = 'text-warning';
                                    } else {
                                        $classificacao = 'Obesidade';
                                        $corClass = 'text-danger';
                                    }
                                @endphp
                                <div class="fw-bold fs-4 {{ $corClass }}">{{ $imcFormatado }}</div>
                                <div class="small {{ $corClass }}">{{ $classificacao }}</div>
                            @else
                                <div class="text-muted">
                                    <i class="fas fa-weight me-2"></i>
                                    Preencha peso e altura
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Histórico -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-history me-2"></i>
                            Histórico
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between px-0">
                                <span>Criado em:</span>
                                <span class="small">{{ $prontuario->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between px-0">
                                <span>Última atualização:</span>
                                <span class="small">{{ $prontuario->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between px-0">
                                <span>Total de prontuários:</span>
                                <span class="badge bg-info">{{ $prontuario->paciente->prontuarios->count() }}</span>
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
                                Salvar Alterações
                            </button>
                            <a href="{{ route('prontuarios.show', $prontuario) }}" class="btn btn-outline-info">
                                <i class="fas fa-eye me-2"></i>
                                Visualizar
                            </a>
                            <a href="{{ route('prontuarios.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>
                                Cancelar
                            </a>
                        </div>

                        <hr>

                        <small class="text-muted">
                            <i class="fas fa-shield-alt me-1"></i>
                            Todas as alterações são registradas no sistema para auditoria.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Quando alterar o médico
            $('#medico_id').change(function() {
                const selectedOption = $(this).find('option:selected');

                if (selectedOption.val()) {
                    $('#medico-nome').text(selectedOption.text().split(' - ')[0]);
                    $('#medico-especialidade').text(selectedOption.data('especialidade'));
                    $('#medico-crm').text(selectedOption.data('crm'));
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

            // Calcular IMC inicial se já houver dados
            calcularIMC();
        });
    </script>
@endsection

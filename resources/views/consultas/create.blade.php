@extends('layouts.modern')

@section('title', 'Agendar Consulta')

@section('breadcrumb')
    <i class="fas fa-home"></i>
    <span>Dashboard</span>
    <i class="fas fa-chevron-right"></i>
    <a href="{{ route('consultas.index') }}">Consultas</a>
    <i class="fas fa-chevron-right"></i>
    <span>Agendar</span>
@endsection

@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">
                    <i class="fas fa-calendar-plus me-2"></i>
                    Agendar Consulta
                </h1>
                <p class="page-subtitle">Agende uma nova consulta médica</p>
            </div>
            <div>
                <a href="{{ route('consultas.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Voltar
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('consultas.store') }}" method="POST">
        @csrf

        <div class="row g-4">
            <div class="col-lg-8">
                <!-- Dados da Consulta -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-calendar-check me-2"></i>
                            Dados da Consulta
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
                                            {{ old('paciente_id') == $paciente->id ? 'selected' : '' }}
                                            data-cpf="{{ $paciente->cpf }}"
                                            data-nascimento="{{ $paciente->data_nascimento->format('d/m/Y') }}">
                                            {{ $paciente->nome }} - {{ $paciente->cpf }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('paciente_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="mt-2">
                                    <small class="text-muted">
                                        <a href="{{ route('pacientes.create') }}" target="_blank">
                                            <i class="fas fa-plus me-1"></i>
                                            Cadastrar novo paciente
                                        </a>
                                    </small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="medico_id" class="form-label">Médico *</label>
                                <select class="form-select @error('medico_id') is-invalid @enderror" id="medico_id"
                                    name="medico_id" required>
                                    <option value="">Selecione um médico...</option>
                                    @foreach ($medicos as $medico)
                                        <option value="{{ $medico->id }}"
                                            {{ old('medico_id') == $medico->id ? 'selected' : '' }}
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

                            <div class="col-md-4">
                                <label for="data_consulta" class="form-label">Data da Consulta *</label>
                                <input type="date" class="form-control @error('data_consulta') is-invalid @enderror"
                                    id="data_consulta" name="data_consulta"
                                    value="{{ old('data_consulta', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required>
                                @error('data_consulta')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="hora_consulta" class="form-label">Horário *</label>
                                <select class="form-select @error('hora_consulta') is-invalid @enderror" id="hora_consulta"
                                    name="hora_consulta" required>
                                    <option value="">Selecione um horário...</option>
                                    @for ($h = 7; $h <= 17; $h++)
                                        @for ($m = 0; $m < 60; $m += 30)
                                            @php
                                                $hora = sprintf('%02d:%02d', $h, $m);
                                                $selected = old('hora_consulta') == $hora ? 'selected' : '';
                                            @endphp
                                            <option value="{{ $hora }}" {{ $selected }}>{{ $hora }}
                                            </option>
                                        @endfor
                                    @endfor
                                </select>
                                @error('hora_consulta')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="tipo_consulta" class="form-label">Tipo de Consulta *</label>
                                <select class="form-select @error('tipo_consulta') is-invalid @enderror" id="tipo_consulta"
                                    name="tipo_consulta" required>
                                    <option value="">Selecione...</option>
                                    <option value="primeira_vez"
                                        {{ old('tipo_consulta') == 'primeira_vez' ? 'selected' : '' }}>
                                        Primeira Vez
                                    </option>
                                    <option value="retorno" {{ old('tipo_consulta') == 'retorno' ? 'selected' : '' }}>
                                        Retorno
                                    </option>
                                    <option value="urgencia" {{ old('tipo_consulta') == 'urgencia' ? 'selected' : '' }}>
                                        Urgência
                                    </option>
                                    <option value="preventiva"
                                        {{ old('tipo_consulta') == 'preventiva' ? 'selected' : '' }}>
                                        Preventiva
                                    </option>
                                </select>
                                @error('tipo_consulta')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Observações -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-notes-medical me-2"></i>
                            Observações e Motivo
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="motivo" class="form-label">Motivo da Consulta</label>
                            <input type="text" class="form-control @error('motivo') is-invalid @enderror" id="motivo"
                                name="motivo" value="{{ old('motivo') }}"
                                placeholder="Ex: Consulta de rotina, dor de cabeça, etc.">
                            @error('motivo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="observacoes" class="form-label">Observações Adicionais</label>
                            <textarea class="form-control @error('observacoes') is-invalid @enderror" id="observacoes" name="observacoes"
                                rows="4" placeholder="Informações relevantes sobre a consulta, sintomas, medicamentos em uso, etc.">{{ old('observacoes') }}</textarea>
                            @error('observacoes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="notificar_paciente"
                                name="notificar_paciente" value="1"
                                {{ old('notificar_paciente', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="notificar_paciente">
                                Enviar notificação por SMS/WhatsApp para o paciente
                            </label>
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
                                <span>Consultas anteriores:</span>
                                <span id="paciente-consultas" class="badge bg-primary">-</span>
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

                <!-- Horários Disponíveis -->
                <div class="card mb-4" id="horarios-disponiveis" style="display: none;">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-clock me-2"></i>
                            Horários Disponíveis
                        </h5>
                    </div>
                    <div class="card-body">
                        <div id="horarios-lista">
                            <div class="text-center">
                                <div class="spinner-border spinner-border-sm" role="status">
                                    <span class="visually-hidden">Carregando...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ações -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-calendar-check me-2"></i>
                                Agendar Consulta
                            </button>
                            <a href="{{ route('consultas.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>
                                Cancelar
                            </a>
                        </div>

                        <hr>

                        <div class="alert alert-info alert-sm">
                            <i class="fas fa-info-circle me-2"></i>
                            <small>
                                O paciente receberá uma notificação com os detalhes da consulta agendada.
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
                    $('#paciente-info').show();

                    // Buscar número de consultas do paciente
                    buscarConsultasPaciente(selectedOption.val());
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

                    verificarHorariosDisponiveis();
                } else {
                    $('#medico-info').hide();
                    $('#horarios-disponiveis').hide();
                }
            });

            // Quando alterar a data
            $('#data_consulta').change(function() {
                verificarHorariosDisponiveis();
            });

            function buscarConsultasPaciente(pacienteId) {
                fetch(`/api/pacientes/${pacienteId}/consultas-count`)
                    .then(response => response.json())
                    .then(data => {
                        $('#paciente-consultas').text(data.count || 0);
                    })
                    .catch(error => {
                        $('#paciente-consultas').text('-');
                    });
            }

            function verificarHorariosDisponiveis() {
                const medicoId = $('#medico_id').val();
                const data = $('#data_consulta').val();

                if (medicoId && data) {
                    $('#horarios-disponiveis').show();
                    $('#horarios-lista').html(`
                <div class="text-center">
                    <div class="spinner-border spinner-border-sm" role="status">
                        <span class="visually-hidden">Carregando...</span>
                    </div>
                </div>
            `);

                    fetch(`/api/medicos/${medicoId}/horarios-disponiveis?data=${data}`)
                        .then(response => response.json())
                        .then(data => {
                            let html = '';

                            if (data.horarios && data.horarios.length > 0) {
                                html = '<div class="row g-2">';
                                data.horarios.forEach(horario => {
                                    const isSelected = $('#hora_consulta').val() === horario;
                                    const btnClass = isSelected ? 'btn-primary' : 'btn-outline-primary';

                                    html += `
                                <div class="col-6">
                                    <button type="button"
                                            class="btn ${btnClass} btn-sm w-100 horario-btn"
                                            data-horario="${horario}">
                                        ${horario}
                                    </button>
                                </div>
                            `;
                                });
                                html += '</div>';
                            } else {
                                html = `
                            <div class="text-center text-muted">
                                <i class="fas fa-calendar-times mb-2"></i>
                                <p class="mb-0">Nenhum horário disponível nesta data</p>
                            </div>
                        `;
                            }

                            $('#horarios-lista').html(html);

                            // Adicionar evento de clique nos botões de horário
                            $('.horario-btn').click(function() {
                                const horario = $(this).data('horario');
                                $('#hora_consulta').val(horario);

                                $('.horario-btn').removeClass('btn-primary').addClass(
                                    'btn-outline-primary');
                                $(this).removeClass('btn-outline-primary').addClass('btn-primary');
                            });
                        })
                        .catch(error => {
                            $('#horarios-lista').html(`
                        <div class="text-center text-danger">
                            <i class="fas fa-exclamation-triangle mb-2"></i>
                            <p class="mb-0">Erro ao carregar horários</p>
                        </div>
                    `);
                        });
                } else {
                    $('#horarios-disponiveis').hide();
                }
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

@extends('layouts.modern')

@section('title', 'Editar Consulta')

@section('breadcrumb')
    <i class="fas fa-home"></i>
    <span>Dashboard</span>
    <i class="fas fa-chevron-right"></i>
    <a href="{{ route('consultas.index') }}">Consultas</a>
    <i class="fas fa-chevron-right"></i>
    <a href="{{ route('consultas.show', $consulta) }}">Consulta #{{ $consulta->id }}</a>
    <i class="fas fa-chevron-right"></i>
    <span>Editar</span>
@endsection

@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">
                    <i class="fas fa-calendar-alt me-2"></i>
                    Editar Consulta
                </h1>
                <p class="page-subtitle">Atualize as informações da consulta #{{ $consulta->id }}</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('consultas.show', $consulta) }}" class="btn btn-outline-info">
                    <i class="fas fa-eye me-2"></i>
                    Visualizar
                </a>
                <a href="{{ route('consultas.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Voltar
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('consultas.update', $consulta) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <div class="col-lg-8">
                <!-- Status da Consulta -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Status atual:</strong>
                            @php
                                $statusLabels = [
                                    'agendada' => 'Agendada',
                                    'em_andamento' => 'Em Andamento',
                                    'concluida' => 'Concluída',
                                    'cancelada' => 'Cancelada',
                                ];
                            @endphp
                            {{ $statusLabels[$consulta->status] ?? $consulta->status }}

                            @if ($consulta->status == 'concluida')
                                <br><small>Consultas concluídas têm edição limitada.</small>
                            @elseif($consulta->status == 'cancelada')
                                <br><small>Consultas canceladas não podem ser editadas.</small>
                            @endif
                        </div>
                    </div>
                </div>

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
                                    name="paciente_id" required {{ $consulta->status != 'agendada' ? 'disabled' : '' }}>
                                    @foreach ($pacientes as $paciente)
                                        <option value="{{ $paciente->id }}"
                                            {{ old('paciente_id', $consulta->paciente_id) == $paciente->id ? 'selected' : '' }}
                                            data-cpf="{{ $paciente->cpf }}"
                                            data-nascimento="{{ $paciente->data_nascimento->format('d/m/Y') }}">
                                            {{ $paciente->nome }} - {{ $paciente->cpf }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($consulta->status != 'agendada')
                                    <input type="hidden" name="paciente_id" value="{{ $consulta->paciente_id }}">
                                    <small class="text-muted">O paciente não pode ser alterado após o início da
                                        consulta.</small>
                                @endif
                                @error('paciente_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="medico_id" class="form-label">Médico *</label>
                                <select class="form-select @error('medico_id') is-invalid @enderror" id="medico_id"
                                    name="medico_id" required {{ $consulta->status == 'concluida' ? 'disabled' : '' }}>
                                    @foreach ($medicos as $medico)
                                        <option value="{{ $medico->id }}"
                                            {{ old('medico_id', $consulta->medico_id) == $medico->id ? 'selected' : '' }}
                                            data-especialidade="{{ $medico->especialidade }}"
                                            data-crm="{{ $medico->crm }}">
                                            {{ $medico->nome }} - {{ $medico->especialidade }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($consulta->status == 'concluida')
                                    <input type="hidden" name="medico_id" value="{{ $consulta->medico_id }}">
                                    <small class="text-muted">O médico não pode ser alterado em consultas
                                        concluídas.</small>
                                @endif
                                @error('medico_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="data_consulta" class="form-label">Data da Consulta *</label>
                                <input type="date" class="form-control @error('data_consulta') is-invalid @enderror"
                                    id="data_consulta" name="data_consulta"
                                    value="{{ old('data_consulta', $consulta->data_consulta->format('Y-m-d')) }}"
                                    min="{{ date('Y-m-d') }}" required
                                    {{ $consulta->status != 'agendada' ? 'readonly' : '' }}>
                                @if ($consulta->status != 'agendada')
                                    <small class="text-muted">A data não pode ser alterada após o início da
                                        consulta.</small>
                                @endif
                                @error('data_consulta')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="hora_consulta" class="form-label">Horário *</label>
                                <select class="form-select @error('hora_consulta') is-invalid @enderror" id="hora_consulta"
                                    name="hora_consulta" required {{ $consulta->status != 'agendada' ? 'disabled' : '' }}>
                                    @for ($h = 7; $h <= 17; $h++)
                                        @for ($m = 0; $m < 60; $m += 30)
                                            @php
                                                $hora = sprintf('%02d:%02d', $h, $m);
                                                $selected =
                                                    old('hora_consulta', $consulta->data_consulta->format('H:i')) ==
                                                    $hora
                                                        ? 'selected'
                                                        : '';
                                            @endphp
                                            <option value="{{ $hora }}" {{ $selected }}>{{ $hora }}
                                            </option>
                                        @endfor
                                    @endfor
                                </select>
                                @if ($consulta->status != 'agendada')
                                    <input type="hidden" name="hora_consulta"
                                        value="{{ $consulta->data_consulta->format('H:i') }}">
                                    <small class="text-muted">O horário não pode ser alterado após o início da
                                        consulta.</small>
                                @endif
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
                                        {{ old('tipo_consulta', $consulta->tipo_consulta) == 'primeira_vez' ? 'selected' : '' }}>
                                        Primeira Vez
                                    </option>
                                    <option value="retorno"
                                        {{ old('tipo_consulta', $consulta->tipo_consulta) == 'retorno' ? 'selected' : '' }}>
                                        Retorno
                                    </option>
                                    <option value="urgencia"
                                        {{ old('tipo_consulta', $consulta->tipo_consulta) == 'urgencia' ? 'selected' : '' }}>
                                        Urgência
                                    </option>
                                    <option value="preventiva"
                                        {{ old('tipo_consulta', $consulta->tipo_consulta) == 'preventiva' ? 'selected' : '' }}>
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
                            <input type="text" class="form-control @error('motivo') is-invalid @enderror"
                                id="motivo" name="motivo" value="{{ old('motivo', $consulta->motivo) }}"
                                placeholder="Ex: Consulta de rotina, dor de cabeça, etc.">
                            @error('motivo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="observacoes" class="form-label">Observações Adicionais</label>
                            <textarea class="form-control @error('observacoes') is-invalid @enderror" id="observacoes" name="observacoes"
                                rows="4" placeholder="Informações relevantes sobre a consulta, sintomas, medicamentos em uso, etc.">{{ old('observacoes', $consulta->observacoes) }}</textarea>
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
                <div class="card mb-4" id="paciente-info">
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
                                    {{ substr($consulta->paciente->nome, 0, 1) }}
                                </div>
                            </div>
                            <div>
                                <div class="fw-bold" id="paciente-nome">{{ $consulta->paciente->nome }}</div>
                                <small class="text-muted" id="paciente-cpf">{{ $consulta->paciente->cpf }}</small>
                            </div>
                        </div>

                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between px-0">
                                <span>Nascimento:</span>
                                <span
                                    id="paciente-nascimento">{{ $consulta->paciente->data_nascimento->format('d/m/Y') }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between px-0">
                                <span>Consultas anteriores:</span>
                                <span id="paciente-consultas"
                                    class="badge bg-primary">{{ $consulta->paciente->consultas->count() - 1 }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informações do Médico -->
                <div class="card mb-4" id="medico-info">
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
                                    {{ substr($consulta->medico->nome, 0, 1) }}
                                </div>
                            </div>
                            <div>
                                <div class="fw-bold" id="medico-nome">{{ $consulta->medico->nome }}</div>
                                <small class="text-muted"
                                    id="medico-especialidade">{{ $consulta->medico->especialidade }}</small>
                            </div>
                        </div>

                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between px-0">
                                <span>CRM:</span>
                                <span id="medico-crm">{{ $consulta->medico->crm }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Histórico da Consulta -->
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
                                <span>Criada em:</span>
                                <span class="small">{{ $consulta->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between px-0">
                                <span>Última atualização:</span>
                                <span class="small">{{ $consulta->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                            @if ($consulta->prontuario)
                                <div class="list-group-item d-flex justify-content-between px-0">
                                    <span>Prontuário:</span>
                                    <span class="badge bg-success">Preenchido</span>
                                </div>
                            @endif
                            @if ($consulta->receitas->count() > 0)
                                <div class="list-group-item d-flex justify-content-between px-0">
                                    <span>Receitas:</span>
                                    <span class="badge bg-info">{{ $consulta->receitas->count() }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Ações -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            @if ($consulta->status != 'cancelada')
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-2"></i>
                                    Salvar Alterações
                                </button>
                            @endif
                            <a href="{{ route('consultas.show', $consulta) }}" class="btn btn-outline-info">
                                <i class="fas fa-eye me-2"></i>
                                Visualizar
                            </a>
                            <a href="{{ route('consultas.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>
                                Cancelar
                            </a>
                        </div>

                        @if ($consulta->status == 'cancelada')
                            <hr>
                            <div class="alert alert-danger alert-sm mb-0">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <small>Consultas canceladas não podem ser editadas.</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Quando selecionar um paciente diferente
            $('#paciente_id').change(function() {
                const selectedOption = $(this).find('option:selected');

                if (selectedOption.val()) {
                    $('#paciente-nome').text(selectedOption.text().split(' - ')[0]);
                    $('#paciente-cpf').text(selectedOption.data('cpf'));
                    $('#paciente-nascimento').text(selectedOption.data('nascimento'));

                    // Buscar número de consultas do paciente
                    buscarConsultasPaciente(selectedOption.val());
                }
            });

            // Quando selecionar um médico diferente
            $('#medico_id').change(function() {
                const selectedOption = $(this).find('option:selected');

                if (selectedOption.val()) {
                    $('#medico-nome').text(selectedOption.text().split(' - ')[0]);
                    $('#medico-especialidade').text(selectedOption.data('especialidade'));
                    $('#medico-crm').text(selectedOption.data('crm'));
                }
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
        });
    </script>
@endsection

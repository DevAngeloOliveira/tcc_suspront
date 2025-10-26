@extends('layouts.modern')

@section('title', 'Editar Exame #' . $exame->id)

@section('breadcrumb')
    <i class="fas fa-home"></i>
    <span>Dashboard</span>
    <i class="fas fa-chevron-right"></i>
    <a href="{{ route('exames.index') }}">Exames</a>
    <i class="fas fa-chevron-right"></i>
    <a href="{{ route('exames.show', $exame) }}">Exame #{{ $exame->id }}</a>
    <i class="fas fa-chevron-right"></i>
    <span>Editar</span>
@endsection

@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">
                    <i class="fas fa-edit me-2"></i>
                    Editar Exame #{{ $exame->id }}
                </h1>
                <p class="page-subtitle">
                    Modifique as informações do exame médico
                </p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('exames.show', $exame) }}" class="btn btn-outline-info">
                    <i class="fas fa-eye me-2"></i>
                    Visualizar
                </a>
                <a href="{{ route('exames.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Voltar
                </a>
            </div>
        </div>
    </div>

    <!-- Avisos importantes -->
    @if ($exame->status == 'concluido')
        <div class="alert alert-warning d-flex align-items-center mb-4">
            <i class="fas fa-exclamation-triangle me-3"></i>
            <div>
                <strong>Atenção:</strong> Este exame já foi concluído. Alterações devem ser feitas com cuidado pois podem
                afetar o histórico médico.
            </div>
        </div>
    @endif

    <form action="{{ route('exames.update', $exame) }}" method="POST" enctype="multipart/form-data" id="form-exame">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <div class="col-lg-8">
                <!-- Informações do Exame -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-microscope me-2"></i>
                            Informações do Exame
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="tipo_exame" class="form-label required">Tipo de Exame</label>
                                <input type="text" class="form-control" id="tipo_exame" name="tipo_exame"
                                    value="{{ old('tipo_exame', $exame->tipo_exame) }}" required
                                    placeholder="Ex: Hemograma Completo, Raio-X de Tórax...">
                                @error('tipo_exame')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="categoria" class="form-label required">Categoria</label>
                                <select class="form-select" id="categoria" name="categoria" required>
                                    <option value="">Selecione a categoria...</option>
                                    <option value="laboratorio"
                                        {{ old('categoria', $exame->categoria) == 'laboratorio' ? 'selected' : '' }}>
                                        Laboratório</option>
                                    <option value="imagem"
                                        {{ old('categoria', $exame->categoria) == 'imagem' ? 'selected' : '' }}>Exame de
                                        Imagem</option>
                                    <option value="cardiologico"
                                        {{ old('categoria', $exame->categoria) == 'cardiologico' ? 'selected' : '' }}>
                                        Cardiológico</option>
                                    <option value="neurologia"
                                        {{ old('categoria', $exame->categoria) == 'neurologia' ? 'selected' : '' }}>
                                        Neurologia</option>
                                    <option value="endoscopia"
                                        {{ old('categoria', $exame->categoria) == 'endoscopia' ? 'selected' : '' }}>
                                        Endoscopia</option>
                                    <option value="biopsia"
                                        {{ old('categoria', $exame->categoria) == 'biopsia' ? 'selected' : '' }}>Biópsia
                                    </option>
                                    <option value="funcional"
                                        {{ old('categoria', $exame->categoria) == 'funcional' ? 'selected' : '' }}>Exame
                                        Funcional</option>
                                    <option value="outros"
                                        {{ old('categoria', $exame->categoria) == 'outros' ? 'selected' : '' }}>Outros
                                    </option>
                                </select>
                                @error('categoria')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="prioridade" class="form-label">Prioridade</label>
                                <select class="form-select" id="prioridade" name="prioridade">
                                    <option value="normal"
                                        {{ old('prioridade', $exame->prioridade) == 'normal' ? 'selected' : '' }}>Normal
                                    </option>
                                    <option value="urgente"
                                        {{ old('prioridade', $exame->prioridade) == 'urgente' ? 'selected' : '' }}>Urgente
                                    </option>
                                    <option value="muito_urgente"
                                        {{ old('prioridade', $exame->prioridade) == 'muito_urgente' ? 'selected' : '' }}>
                                        Muito Urgente</option>
                                </select>
                                @error('prioridade')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="solicitado"
                                        {{ old('status', $exame->status) == 'solicitado' ? 'selected' : '' }}>Solicitado
                                    </option>
                                    <option value="agendado"
                                        {{ old('status', $exame->status) == 'agendado' ? 'selected' : '' }}>Agendado
                                    </option>
                                    <option value="em_andamento"
                                        {{ old('status', $exame->status) == 'em_andamento' ? 'selected' : '' }}>Em
                                        Andamento</option>
                                    <option value="concluido"
                                        {{ old('status', $exame->status) == 'concluido' ? 'selected' : '' }}>Concluído
                                    </option>
                                    <option value="cancelado"
                                        {{ old('status', $exame->status) == 'cancelado' ? 'selected' : '' }}>Cancelado
                                    </option>
                                </select>
                                @error('status')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" id="jejum_necessario"
                                        name="jejum_necessario" value="1"
                                        {{ old('jejum_necessario', $exame->jejum_necessario) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="jejum_necessario">
                                        <i class="fas fa-clock text-warning me-1"></i>
                                        Jejum Necessário
                                    </label>
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="descricao" class="form-label">Descrição/Observações</label>
                                <textarea class="form-control" id="descricao" name="descricao" rows="3"
                                    placeholder="Descreva o motivo da solicitação, sintomas observados, etc.">{{ old('descricao', $exame->descricao) }}</textarea>
                                @error('descricao')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Agendamento -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-calendar me-2"></i>
                            Agendamento do Exame
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="data_exame" class="form-label">Data do Exame</label>
                                <input type="date" class="form-control" id="data_exame" name="data_exame"
                                    value="{{ old('data_exame', $exame->data_exame?->format('Y-m-d')) }}">
                                @error('data_exame')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="hora_exame" class="form-label">Horário</label>
                                <input type="time" class="form-control" id="hora_exame" name="hora_exame"
                                    value="{{ old('hora_exame', $exame->hora_exame) }}">
                                @error('hora_exame')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="tempo_jejum" class="form-label">Tempo de Jejum (horas)</label>
                                <select class="form-select" id="tempo_jejum" name="tempo_jejum"
                                    {{ !$exame->jejum_necessario ? 'disabled' : '' }}>
                                    <option value="">Não se aplica</option>
                                    <option value="8"
                                        {{ old('tempo_jejum', $exame->tempo_jejum) == '8' ? 'selected' : '' }}>8 horas
                                    </option>
                                    <option value="12"
                                        {{ old('tempo_jejum', $exame->tempo_jejum) == '12' ? 'selected' : '' }}>12 horas
                                    </option>
                                    <option value="14"
                                        {{ old('tempo_jejum', $exame->tempo_jejum) == '14' ? 'selected' : '' }}>14 horas
                                    </option>
                                    <option value="16"
                                        {{ old('tempo_jejum', $exame->tempo_jejum) == '16' ? 'selected' : '' }}>16 horas
                                    </option>
                                </select>
                                @error('tempo_jejum')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="laboratorio" class="form-label">Laboratório/Local</label>
                                <input type="text" class="form-control" id="laboratorio" name="laboratorio"
                                    value="{{ old('laboratorio', $exame->laboratorio) }}"
                                    placeholder="Nome do laboratório ou local do exame">
                                @error('laboratorio')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="local_exame" class="form-label">Endereço/Sala</label>
                                <input type="text" class="form-control" id="local_exame" name="local_exame"
                                    value="{{ old('local_exame', $exame->local_exame) }}"
                                    placeholder="Endereço ou sala específica">
                                @error('local_exame')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Preparo para o Exame -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-clipboard-list me-2"></i>
                            Preparo e Orientações
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="preparo" class="form-label">Instruções de Preparo</label>
                                <textarea class="form-control" id="preparo" name="preparo" rows="4"
                                    placeholder="Descreva as instruções de preparo para o exame...">{{ old('preparo', $exame->preparo) }}</textarea>
                                @error('preparo')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="observacoes_preparo" class="form-label">Observações Adicionais</label>
                                <textarea class="form-control" id="observacoes_preparo" name="observacoes_preparo" rows="2"
                                    placeholder="Observações especiais sobre o preparo...">{{ old('observacoes_preparo', $exame->observacoes_preparo) }}</textarea>
                                @error('observacoes_preparo')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Resultados (se o exame estiver concluído) -->
                @if ($exame->status == 'concluido' || old('status') == 'concluido')
                    <div class="card mb-4" id="secao-resultados">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-file-medical me-2"></i>
                                Resultados do Exame
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="resultado" class="form-label">Resultado</label>
                                    <textarea class="form-control" id="resultado" name="resultado" rows="5"
                                        placeholder="Descreva o resultado do exame...">{{ old('resultado', $exame->resultado) }}</textarea>
                                    @error('resultado')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="observacoes_resultado" class="form-label">Observações do Resultado</label>
                                    <textarea class="form-control" id="observacoes_resultado" name="observacoes_resultado" rows="3"
                                        placeholder="Observações sobre o resultado...">{{ old('observacoes_resultado', $exame->observacoes_resultado) }}</textarea>
                                    @error('observacoes_resultado')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="valores_referencia" class="form-label">Valores de Referência</label>
                                    <textarea class="form-control" id="valores_referencia" name="valores_referencia" rows="3"
                                        placeholder="Valores normais de referência...">{{ old('valores_referencia', $exame->valores_referencia) }}</textarea>
                                    @error('valores_referencia')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="arquivo_resultado" class="form-label">Anexar Arquivo do Resultado</label>
                                    <input type="file" class="form-control" id="arquivo_resultado"
                                        name="arquivo_resultado" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                    <small class="text-muted">Formatos aceitos: PDF, JPG, PNG, DOC, DOCX (máx.
                                        10MB)</small>
                                    @if ($exame->arquivo_resultado)
                                        <div class="mt-2">
                                            <small class="text-success">
                                                <i class="fas fa-file me-1"></i>
                                                Arquivo atual: {{ basename($exame->arquivo_resultado) }}
                                            </small>
                                        </div>
                                    @endif
                                    @error('arquivo_resultado')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Botões de Ação -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>
                                Salvar Alterações
                            </button>
                            <button type="button" class="btn btn-outline-info" onclick="visualizarExame()">
                                <i class="fas fa-eye me-2"></i>
                                Pré-visualizar
                            </button>
                            <a href="{{ route('exames.show', $exame) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>
                                Cancelar
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Informações do Paciente (Não editáveis) -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-user me-2"></i>
                            Dados do Paciente
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Informação:</strong> Os dados do paciente não podem ser alterados no exame.
                        </div>

                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar-lg me-3">
                                <div class="avatar-title rounded-circle bg-primary">
                                    {{ substr($exame->paciente->nome, 0, 1) }}
                                </div>
                            </div>
                            <div>
                                <div class="fw-bold">{{ $exame->paciente->nome }}</div>
                                <small class="text-muted">{{ $exame->paciente->cpf }}</small>
                            </div>
                        </div>

                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between px-0">
                                <span>Idade:</span>
                                <span>{{ $exame->paciente->data_nascimento->age }} anos</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between px-0">
                                <span>Sexo:</span>
                                <span>{{ $exame->paciente->sexo == 'M' ? 'Masculino' : 'Feminino' }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between px-0">
                                <span>Cartão SUS:</span>
                                <span class="small">{{ $exame->paciente->cartao_sus }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Seleção do Médico -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-user-md me-2"></i>
                            Médico Solicitante
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="medico_id" class="form-label required">Selecionar Médico</label>
                            <select class="form-select" id="medico_id" name="medico_id" required>
                                <option value="">Escolha o médico...</option>
                                @foreach ($medicos as $medico)
                                    <option value="{{ $medico->id }}"
                                        {{ old('medico_id', $exame->medico_id) == $medico->id ? 'selected' : '' }}>
                                        {{ $medico->nome }} - {{ $medico->especialidade }}
                                    </option>
                                @endforeach
                            </select>
                            @error('medico_id')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Informações do médico selecionado -->
                        <div id="medico-info" class="{{ old('medico_id', $exame->medico_id) ? '' : 'd-none' }}">
                            <div class="bg-light p-3 rounded">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-lg me-3">
                                        <div class="avatar-title rounded-circle bg-success" id="medico-avatar">
                                            {{ substr($exame->medico->nome, 0, 1) }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fw-bold" id="medico-nome">{{ $exame->medico->nome }}</div>
                                        <small class="text-muted"
                                            id="medico-especialidade">{{ $exame->medico->especialidade }}</small>
                                    </div>
                                </div>
                                <hr>
                                <div class="small">
                                    <strong>CRM:</strong> <span id="medico-crm">{{ $exame->medico->crm }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Consulta Relacionada -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-calendar-check me-2"></i>
                            Consulta Relacionada
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="consulta_id" class="form-label">Relacionar com Consulta</label>
                            <select class="form-select" id="consulta_id" name="consulta_id">
                                <option value="">Nenhuma consulta</option>
                                @if ($exame->consulta)
                                    <option value="{{ $exame->consulta->id }}" selected>
                                        Consulta #{{ $exame->consulta->id }} -
                                        {{ $exame->consulta->data_consulta->format('d/m/Y') }}
                                    </option>
                                @endif
                            </select>
                            @error('consulta_id')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Vincule o exame a uma consulta específica se aplicável
                        </small>
                    </div>
                </div>

                <!-- Log de Alterações -->
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
                                <span class="small">{{ $exame->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between px-0">
                                <span>Última alteração:</span>
                                <span class="small">{{ $exame->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                            @if ($exame->created_at != $exame->updated_at)
                                <div class="list-group-item px-0">
                                    <small class="text-warning">
                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                        Este exame já foi modificado anteriormente
                                    </small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Ações Rápidas -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-bolt me-2"></i>
                            Ações Rápidas
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('exames.show', $exame) }}" class="btn btn-outline-info">
                                <i class="fas fa-eye me-2"></i>
                                Visualizar Exame
                            </a>
                            @if ($exame->consulta)
                                <a href="{{ route('consultas.show', $exame->consulta) }}"
                                    class="btn btn-outline-success">
                                    <i class="fas fa-calendar-check me-2"></i>
                                    Ver Consulta
                                </a>
                            @endif
                            <a href="{{ route('pacientes.show', $exame->paciente) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-user me-2"></i>
                                Perfil do Paciente
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('scripts')
    <script>
        // Controle do jejum
        document.getElementById('jejum_necessario').addEventListener('change', function() {
            const tempoJejum = document.getElementById('tempo_jejum');
            tempoJejum.disabled = !this.checked;
            if (!this.checked) {
                tempoJejum.value = '';
            }
        });

        // Controle da seção de resultados baseado no status
        document.getElementById('status').addEventListener('change', function() {
            const secaoResultados = document.getElementById('secao-resultados');
            if (this.value === 'concluido') {
                if (!secaoResultados) {
                    // Criar seção de resultados dinamicamente se não existir
                    criarSecaoResultados();
                } else {
                    secaoResultados.style.display = 'block';
                }
            } else {
                if (secaoResultados) {
                    secaoResultados.style.display = 'none';
                }
            }
        });

        function criarSecaoResultados() {
            const container = document.querySelector('.col-lg-8');
            const ultimoCard = container.querySelector('.card:nth-last-child(2)');

            const secaoResultados = document.createElement('div');
            secaoResultados.className = 'card mb-4';
            secaoResultados.id = 'secao-resultados';
            secaoResultados.innerHTML = `
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-file-medical me-2"></i>
                    Resultados do Exame
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label for="resultado" class="form-label">Resultado</label>
                        <textarea class="form-control" id="resultado" name="resultado" rows="5"
                                  placeholder="Descreva o resultado do exame..."></textarea>
                    </div>
                    <div class="col-md-6">
                        <label for="observacoes_resultado" class="form-label">Observações do Resultado</label>
                        <textarea class="form-control" id="observacoes_resultado" name="observacoes_resultado" rows="3"
                                  placeholder="Observações sobre o resultado..."></textarea>
                    </div>
                    <div class="col-md-6">
                        <label for="valores_referencia" class="form-label">Valores de Referência</label>
                        <textarea class="form-control" id="valores_referencia" name="valores_referencia" rows="3"
                                  placeholder="Valores normais de referência..."></textarea>
                    </div>
                    <div class="col-12">
                        <label for="arquivo_resultado" class="form-label">Anexar Arquivo do Resultado</label>
                        <input type="file" class="form-control" id="arquivo_resultado" name="arquivo_resultado"
                               accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                        <small class="text-muted">Formatos aceitos: PDF, JPG, PNG, DOC, DOCX (máx. 10MB)</small>
                    </div>
                </div>
            </div>
        `;

            ultimoCard.parentNode.insertBefore(secaoResultados, ultimoCard);
        }

        // Informações do médico
        document.getElementById('medico_id').addEventListener('change', function() {
            const medicoId = this.value;
            const medicoInfo = document.getElementById('medico-info');

            if (medicoId) {
                const selectedOption = this.options[this.selectedIndex];
                const texto = selectedOption.textContent;
                const [nome, especialidade] = texto.split(' - ');

                document.getElementById('medico-nome').textContent = nome;
                document.getElementById('medico-especialidade').textContent = especialidade;
                document.getElementById('medico-avatar').textContent = nome.charAt(0);

                // Buscar CRM do médico via AJAX se necessário
                fetch(`/api/medicos/${medicoId}`)
                    .then(response => response.json())
                    .then(medico => {
                        document.getElementById('medico-crm').textContent = medico.crm;
                    })
                    .catch(error => {
                        console.error('Erro ao buscar médico:', error);
                    });

                medicoInfo.classList.remove('d-none');
            } else {
                medicoInfo.classList.add('d-none');
            }
        });

        function visualizarExame() {
            // Implementar pré-visualização do exame
            alert('Funcionalidade de pré-visualização em desenvolvimento');
        }

        // Validação do formulário
        document.getElementById('form-exame').addEventListener('submit', function(e) {
            const medicoId = document.getElementById('medico_id').value;
            const tipoExame = document.getElementById('tipo_exame').value;
            const categoria = document.getElementById('categoria').value;
            const status = document.getElementById('status').value;

            if (!medicoId || !tipoExame || !categoria) {
                e.preventDefault();
                alert('Por favor, preencha todos os campos obrigatórios.');
                return;
            }

            // Validação específica para exames concluídos
            if (status === 'concluido') {
                const resultado = document.getElementById('resultado');
                if (resultado && !resultado.value.trim()) {
                    e.preventDefault();
                    alert('Para exames concluídos, é necessário informar o resultado.');
                    resultado.focus();
                    return;
                }
            }
        });

        // Auto-save (opcional)
        let autoSaveTimer;

        function resetAutoSave() {
            clearTimeout(autoSaveTimer);
            autoSaveTimer = setTimeout(function() {
                // Implementar auto-save se necessário
                console.log('Auto-save triggered');
            }, 30000); // 30 segundos
        }

        // Monitorar mudanças nos campos para auto-save
        document.querySelectorAll('input, textarea, select').forEach(function(element) {
            element.addEventListener('change', resetAutoSave);
        });

        // Validação de arquivo
        document.getElementById('arquivo_resultado')?.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const maxSize = 10 * 1024 * 1024; // 10MB
                if (file.size > maxSize) {
                    alert('O arquivo é muito grande. O tamanho máximo é de 10MB.');
                    this.value = '';
                }
            }
        });
    </script>
@endsection

@extends('layouts.modern')

@section('title', 'Editar Receita #' . $receita->id)

@section('breadcrumb')
    <i class="fas fa-home"></i>
    <span>Dashboard</span>
    <i class="fas fa-chevron-right"></i>
    <a href="{{ route('receitas.index') }}">Receitas</a>
    <i class="fas fa-chevron-right"></i>
    <a href="{{ route('receitas.show', $receita) }}">Receita #{{ $receita->id }}</a>
    <i class="fas fa-chevron-right"></i>
    <span>Editar</span>
@endsection

@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">
                    <i class="fas fa-edit me-2"></i>
                    Editar Receita #{{ $receita->id }}
                </h1>
                <p class="page-subtitle">
                    Modifique as informações da receita médica
                </p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('receitas.show', $receita) }}" class="btn btn-outline-info">
                    <i class="fas fa-eye me-2"></i>
                    Visualizar
                </a>
                <a href="{{ route('receitas.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Voltar
                </a>
            </div>
        </div>
    </div>

    <!-- Avisos importantes -->
    <div class="alert alert-warning d-flex align-items-center mb-4">
        <i class="fas fa-exclamation-triangle me-3"></i>
        <div>
            <strong>Atenção:</strong> Alterações em receitas médicas devem ser feitas com cuidado.
            Mudanças importantes podem afetar o tratamento do paciente.
        </div>
    </div>

    <form action="{{ route('receitas.update', $receita) }}" method="POST" id="form-receita">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <div class="col-lg-8">
                <!-- Informações do Medicamento -->
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
                                <label for="medicamento" class="form-label required">Nome do Medicamento</label>
                                <input type="text" class="form-control" id="medicamento" name="medicamento"
                                    value="{{ old('medicamento', $receita->medicamento) }}" required>
                                @error('medicamento')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="forma_farmaceutica" class="form-label">Forma Farmacêutica</label>
                                <select class="form-select" id="forma_farmaceutica" name="forma_farmaceutica">
                                    <option value="">Selecione...</option>
                                    <option value="comprimido"
                                        {{ old('forma_farmaceutica', $receita->forma_farmaceutica) == 'comprimido' ? 'selected' : '' }}>
                                        Comprimido</option>
                                    <option value="capsula"
                                        {{ old('forma_farmaceutica', $receita->forma_farmaceutica) == 'capsula' ? 'selected' : '' }}>
                                        Cápsula</option>
                                    <option value="xarope"
                                        {{ old('forma_farmaceutica', $receita->forma_farmaceutica) == 'xarope' ? 'selected' : '' }}>
                                        Xarope</option>
                                    <option value="solucao"
                                        {{ old('forma_farmaceutica', $receita->forma_farmaceutica) == 'solucao' ? 'selected' : '' }}>
                                        Solução</option>
                                    <option value="suspensao"
                                        {{ old('forma_farmaceutica', $receita->forma_farmaceutica) == 'suspensao' ? 'selected' : '' }}>
                                        Suspensão</option>
                                    <option value="pomada"
                                        {{ old('forma_farmaceutica', $receita->forma_farmaceutica) == 'pomada' ? 'selected' : '' }}>
                                        Pomada</option>
                                    <option value="creme"
                                        {{ old('forma_farmaceutica', $receita->forma_farmaceutica) == 'creme' ? 'selected' : '' }}>
                                        Creme</option>
                                    <option value="gel"
                                        {{ old('forma_farmaceutica', $receita->forma_farmaceutica) == 'gel' ? 'selected' : '' }}>
                                        Gel</option>
                                    <option value="ampola"
                                        {{ old('forma_farmaceutica', $receita->forma_farmaceutica) == 'ampola' ? 'selected' : '' }}>
                                        Ampola</option>
                                    <option value="frasco"
                                        {{ old('forma_farmaceutica', $receita->forma_farmaceutica) == 'frasco' ? 'selected' : '' }}>
                                        Frasco</option>
                                </select>
                                @error('forma_farmaceutica')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="principio_ativo" class="form-label">Princípio Ativo</label>
                                <input type="text" class="form-control" id="principio_ativo" name="principio_ativo"
                                    value="{{ old('principio_ativo', $receita->principio_ativo) }}"
                                    placeholder="Ex: Paracetamol, Ibuprofeno">
                                @error('principio_ativo')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="concentracao" class="form-label">Concentração</label>
                                <input type="text" class="form-control" id="concentracao" name="concentracao"
                                    value="{{ old('concentracao', $receita->concentracao) }}"
                                    placeholder="Ex: 500mg, 100mg/ml">
                                @error('concentracao')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="quantidade" class="form-label">Quantidade Total</label>
                                <input type="text" class="form-control" id="quantidade" name="quantidade"
                                    value="{{ old('quantidade', $receita->quantidade) }}"
                                    placeholder="Ex: 30 comprimidos, 120ml">
                                @error('quantidade')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="via_administracao" class="form-label">Via de Administração</label>
                                <select class="form-select" id="via_administracao" name="via_administracao">
                                    <option value="">Selecione...</option>
                                    <option value="oral"
                                        {{ old('via_administracao', $receita->via_administracao) == 'oral' ? 'selected' : '' }}>
                                        Oral</option>
                                    <option value="sublingual"
                                        {{ old('via_administracao', $receita->via_administracao) == 'sublingual' ? 'selected' : '' }}>
                                        Sublingual</option>
                                    <option value="topica"
                                        {{ old('via_administracao', $receita->via_administracao) == 'topica' ? 'selected' : '' }}>
                                        Tópica</option>
                                    <option value="intramuscular"
                                        {{ old('via_administracao', $receita->via_administracao) == 'intramuscular' ? 'selected' : '' }}>
                                        Intramuscular</option>
                                    <option value="intravenosa"
                                        {{ old('via_administracao', $receita->via_administracao) == 'intravenosa' ? 'selected' : '' }}>
                                        Intravenosa</option>
                                    <option value="subcutanea"
                                        {{ old('via_administracao', $receita->via_administracao) == 'subcutanea' ? 'selected' : '' }}>
                                        Subcutânea</option>
                                    <option value="oftalmica"
                                        {{ old('via_administracao', $receita->via_administracao) == 'oftalmica' ? 'selected' : '' }}>
                                        Oftálmica</option>
                                    <option value="nasal"
                                        {{ old('via_administracao', $receita->via_administracao) == 'nasal' ? 'selected' : '' }}>
                                        Nasal</option>
                                    <option value="auricular"
                                        {{ old('via_administracao', $receita->via_administracao) == 'auricular' ? 'selected' : '' }}>
                                        Auricular</option>
                                    <option value="retal"
                                        {{ old('via_administracao', $receita->via_administracao) == 'retal' ? 'selected' : '' }}>
                                        Retal</option>
                                    <option value="vaginal"
                                        {{ old('via_administracao', $receita->via_administracao) == 'vaginal' ? 'selected' : '' }}>
                                        Vaginal</option>
                                </select>
                                @error('via_administracao')
                                    <div class="text-danger small">{{ $message }}</div>
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
                            Posologia e Administração
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="dosagem" class="form-label required">Dosagem</label>
                                <input type="text" class="form-control" id="dosagem" name="dosagem"
                                    value="{{ old('dosagem', $receita->dosagem) }}" required
                                    placeholder="Ex: 1 comprimido, 10ml">
                                @error('dosagem')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="frequencia" class="form-label required">Frequência</label>
                                <select class="form-select" id="frequencia" name="frequencia" required>
                                    <option value="">Selecione...</option>
                                    <option value="1x ao dia"
                                        {{ old('frequencia', $receita->frequencia) == '1x ao dia' ? 'selected' : '' }}>1x
                                        ao dia</option>
                                    <option value="2x ao dia"
                                        {{ old('frequencia', $receita->frequencia) == '2x ao dia' ? 'selected' : '' }}>2x
                                        ao dia</option>
                                    <option value="3x ao dia"
                                        {{ old('frequencia', $receita->frequencia) == '3x ao dia' ? 'selected' : '' }}>3x
                                        ao dia</option>
                                    <option value="4x ao dia"
                                        {{ old('frequencia', $receita->frequencia) == '4x ao dia' ? 'selected' : '' }}>4x
                                        ao dia</option>
                                    <option value="De 8 em 8 horas"
                                        {{ old('frequencia', $receita->frequencia) == 'De 8 em 8 horas' ? 'selected' : '' }}>
                                        De 8 em 8 horas</option>
                                    <option value="De 12 em 12 horas"
                                        {{ old('frequencia', $receita->frequencia) == 'De 12 em 12 horas' ? 'selected' : '' }}>
                                        De 12 em 12 horas</option>
                                    <option value="De 6 em 6 horas"
                                        {{ old('frequencia', $receita->frequencia) == 'De 6 em 6 horas' ? 'selected' : '' }}>
                                        De 6 em 6 horas</option>
                                    <option value="Se necessário"
                                        {{ old('frequencia', $receita->frequencia) == 'Se necessário' ? 'selected' : '' }}>
                                        Se necessário</option>
                                    <option value="Conforme orientação"
                                        {{ old('frequencia', $receita->frequencia) == 'Conforme orientação' ? 'selected' : '' }}>
                                        Conforme orientação</option>
                                </select>
                                @error('frequencia')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="duracao" class="form-label">Duração do Tratamento</label>
                                <input type="text" class="form-control" id="duracao" name="duracao"
                                    value="{{ old('duracao', $receita->duracao) }}" placeholder="Ex: 7 dias, 30 dias">
                                @error('duracao')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Calculadora de Dosagem -->
                            <div class="col-12">
                                <div class="bg-light p-3 rounded">
                                    <h6><i class="fas fa-calculator me-2"></i>Calculadora de Dosagem</h6>
                                    <div class="row g-2">
                                        <div class="col-md-3">
                                            <label class="form-label small">Peso do Paciente (kg)</label>
                                            <input type="number" class="form-control form-control-sm" id="peso_calc"
                                                step="0.1">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small">mg/kg/dose</label>
                                            <input type="number" class="form-control form-control-sm" id="mg_kg_dose"
                                                step="0.01">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small">Doses por dia</label>
                                            <input type="number" class="form-control form-control-sm" id="doses_dia"
                                                value="1">
                                        </div>
                                        <div class="col-md-3">
                                            <button type="button" class="btn btn-outline-primary btn-sm mt-4"
                                                onclick="calcularDosagem()">
                                                <i class="fas fa-calculator me-1"></i>Calcular
                                            </button>
                                        </div>
                                    </div>
                                    <div id="resultado-calculo" class="mt-2"></div>
                                </div>
                            </div>

                            <!-- Características especiais -->
                            <div class="col-12">
                                <label class="form-label">Características Especiais</label>
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="uso_continuo"
                                                name="uso_continuo" value="1"
                                                {{ old('uso_continuo', $receita->uso_continuo) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="uso_continuo">
                                                <i class="fas fa-clock text-warning me-1"></i>
                                                Uso Contínuo
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="controle_especial"
                                                name="controle_especial" value="1"
                                                {{ old('controle_especial', $receita->controle_especial) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="controle_especial">
                                                <i class="fas fa-exclamation-triangle text-danger me-1"></i>
                                                Medicamento de Controle Especial
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Orientações -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Orientações e Observações
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="orientacoes" class="form-label">Orientações de Uso</label>
                                <textarea class="form-control" id="orientacoes" name="orientacoes" rows="4"
                                    placeholder="Ex: Tomar com alimentos, evitar álcool...">{{ old('orientacoes', $receita->orientacoes) }}</textarea>
                                @error('orientacoes')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="observacoes" class="form-label">Observações Adicionais</label>
                                <textarea class="form-control" id="observacoes" name="observacoes" rows="4"
                                    placeholder="Observações especiais sobre o tratamento...">{{ old('observacoes', $receita->observacoes) }}</textarea>
                                @error('observacoes')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Orientações pré-definidas -->
                        <div class="mt-3">
                            <label class="form-label">Orientações Comuns</label>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-outline-secondary btn-sm"
                                    onclick="adicionarOrientacao('Tomar com alimentos')">
                                    Tomar com alimentos
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm"
                                    onclick="adicionarOrientacao('Tomar em jejum')">
                                    Tomar em jejum
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm"
                                    onclick="adicionarOrientacao('Evitar álcool durante o tratamento')">
                                    Evitar álcool
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm"
                                    onclick="adicionarOrientacao('Não interromper o tratamento')">
                                    Não interromper
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm"
                                    onclick="adicionarOrientacao('Tomar com bastante água')">
                                    Com bastante água
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botões de Ação -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>
                                Salvar Alterações
                            </button>
                            <button type="button" class="btn btn-outline-info" onclick="visualizarReceita()">
                                <i class="fas fa-eye me-2"></i>
                                Pré-visualizar
                            </button>
                            <a href="{{ route('receitas.show', $receita) }}" class="btn btn-outline-secondary">
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
                            <strong>Informação:</strong> Os dados do paciente não podem ser alterados na receita.
                        </div>

                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar-lg me-3">
                                <div class="avatar-title rounded-circle bg-primary">
                                    {{ substr($receita->paciente->nome, 0, 1) }}
                                </div>
                            </div>
                            <div>
                                <div class="fw-bold">{{ $receita->paciente->nome }}</div>
                                <small class="text-muted">{{ $receita->paciente->cpf }}</small>
                            </div>
                        </div>

                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between px-0">
                                <span>Idade:</span>
                                <span>{{ $receita->paciente->data_nascimento->age }} anos</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between px-0">
                                <span>Sexo:</span>
                                <span>{{ $receita->paciente->sexo == 'M' ? 'Masculino' : 'Feminino' }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between px-0">
                                <span>Cartão SUS:</span>
                                <span class="small">{{ $receita->paciente->cartao_sus }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informações do Médico (Não editáveis) -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-user-md me-2"></i>
                            Médico Prescritor
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Atenção:</strong> O médico prescritor não pode ser alterado.
                        </div>

                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar-lg me-3">
                                <div class="avatar-title rounded-circle bg-success">
                                    {{ substr($receita->medico->nome, 0, 1) }}
                                </div>
                            </div>
                            <div>
                                <div class="fw-bold">{{ $receita->medico->nome }}</div>
                                <small class="text-muted">{{ $receita->medico->especialidade }}</small>
                            </div>
                        </div>

                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between px-0">
                                <span>CRM:</span>
                                <span>{{ $receita->medico->crm }}</span>
                            </div>
                        </div>
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
                                <span>Criada em:</span>
                                <span class="small">{{ $receita->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between px-0">
                                <span>Última alteração:</span>
                                <span class="small">{{ $receita->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                            @if ($receita->created_at != $receita->updated_at)
                                <div class="list-group-item px-0">
                                    <small class="text-warning">
                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                        Esta receita já foi modificada anteriormente
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
                            <a href="{{ route('receitas.show', $receita) }}" class="btn btn-outline-info">
                                <i class="fas fa-eye me-2"></i>
                                Visualizar Receita
                            </a>
                            @if ($receita->consulta)
                                <a href="{{ route('consultas.show', $receita->consulta) }}"
                                    class="btn btn-outline-success">
                                    <i class="fas fa-calendar-check me-2"></i>
                                    Ver Consulta
                                </a>
                            @endif
                            <a href="{{ route('pacientes.show', $receita->paciente) }}"
                                class="btn btn-outline-secondary">
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
        function calcularDosagem() {
            const peso = parseFloat(document.getElementById('peso_calc').value);
            const mgKgDose = parseFloat(document.getElementById('mg_kg_dose').value);
            const dosesDia = parseInt(document.getElementById('doses_dia').value);

            if (peso && mgKgDose && dosesDia) {
                const dosePorToma = peso * mgKgDose;
                const doseTotal = dosePorToma * dosesDia;

                document.getElementById('resultado-calculo').innerHTML = `
                <div class="alert alert-success">
                    <strong>Resultado:</strong><br>
                    Dose por tomada: ${dosePorToma.toFixed(1)}mg<br>
                    Dose total diária: ${doseTotal.toFixed(1)}mg
                </div>
            `;
            } else {
                document.getElementById('resultado-calculo').innerHTML = `
                <div class="alert alert-danger">
                    <strong>Erro:</strong> Preencha todos os campos para calcular.
                </div>
            `;
            }
        }

        function adicionarOrientacao(texto) {
            const orientacoesField = document.getElementById('orientacoes');
            const valorAtual = orientacoesField.value;

            if (valorAtual) {
                orientacoesField.value = valorAtual + '\n' + texto;
            } else {
                orientacoesField.value = texto;
            }
        }

        function visualizarReceita() {
            // Implementar pré-visualização da receita
            alert('Funcionalidade de pré-visualização em desenvolvimento');
        }

        // Validação do formulário
        document.getElementById('form-receita').addEventListener('submit', function(e) {
            const medicamento = document.getElementById('medicamento').value;
            const dosagem = document.getElementById('dosagem').value;
            const frequencia = document.getElementById('frequencia').value;

            if (!medicamento || !dosagem || !frequencia) {
                e.preventDefault();
                alert('Por favor, preencha todos os campos obrigatórios (medicamento, dosagem e frequência).');
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
    </script>
@endsection

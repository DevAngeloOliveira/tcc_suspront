@extends('layouts.modern')

@section('title', 'Novo Exame')

@section('breadcrumb')
    <i class="fas fa-home"></i>
    <span>Dashboard</span>
    <i class="fas fa-chevron-right"></i>
    <a href="{{ route('exames.index') }}">Exames</a>
    <i class="fas fa-chevron-right"></i>
    <span>Novo Exame</span>
@endsection

@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">
                    <i class="fas fa-plus me-2"></i>
                    Solicitar Novo Exame
                </h1>
                <p class="page-subtitle">
                    Preencha as informações para solicitar um exame médico
                </p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('exames.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Voltar
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('exames.store') }}" method="POST" enctype="multipart/form-data" id="form-exame">
        @csrf

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
                                    value="{{ old('tipo_exame') }}" required
                                    placeholder="Ex: Hemograma Completo, Raio-X de Tórax...">
                                @error('tipo_exame')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="categoria" class="form-label required">Categoria</label>
                                <select class="form-select" id="categoria" name="categoria" required>
                                    <option value="">Selecione a categoria...</option>
                                    <option value="laboratorio" {{ old('categoria') == 'laboratorio' ? 'selected' : '' }}>
                                        Laboratório</option>
                                    <option value="imagem" {{ old('categoria') == 'imagem' ? 'selected' : '' }}>Exame de
                                        Imagem</option>
                                    <option value="cardiologico" {{ old('categoria') == 'cardiologico' ? 'selected' : '' }}>
                                        Cardiológico</option>
                                    <option value="neurologia" {{ old('categoria') == 'neurologia' ? 'selected' : '' }}>
                                        Neurologia</option>
                                    <option value="endoscopia" {{ old('categoria') == 'endoscopia' ? 'selected' : '' }}>
                                        Endoscopia</option>
                                    <option value="biopsia" {{ old('categoria') == 'biopsia' ? 'selected' : '' }}>Biópsia
                                    </option>
                                    <option value="funcional" {{ old('categoria') == 'funcional' ? 'selected' : '' }}>Exame
                                        Funcional</option>
                                    <option value="outros" {{ old('categoria') == 'outros' ? 'selected' : '' }}>Outros
                                    </option>
                                </select>
                                @error('categoria')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Lista de exames sugeridos com base na categoria -->
                            <div class="col-12" id="exames-sugeridos" style="display: none;">
                                <div class="bg-light p-3 rounded">
                                    <h6><i class="fas fa-lightbulb me-2"></i>Exames Sugeridos</h6>
                                    <div id="lista-exames-sugeridos" class="d-flex flex-wrap gap-2"></div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label for="prioridade" class="form-label">Prioridade</label>
                                <select class="form-select" id="prioridade" name="prioridade">
                                    <option value="normal" {{ old('prioridade') == 'normal' ? 'selected' : '' }}>Normal
                                    </option>
                                    <option value="urgente" {{ old('prioridade') == 'urgente' ? 'selected' : '' }}>Urgente
                                    </option>
                                    <option value="muito_urgente"
                                        {{ old('prioridade') == 'muito_urgente' ? 'selected' : '' }}>Muito Urgente</option>
                                </select>
                                @error('prioridade')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="status" class="form-label">Status Inicial</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="solicitado" {{ old('status') == 'solicitado' ? 'selected' : '' }}>
                                        Solicitado</option>
                                    <option value="agendado" {{ old('status') == 'agendado' ? 'selected' : '' }}>Agendado
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
                                        {{ old('jejum_necessario') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="jejum_necessario">
                                        <i class="fas fa-clock text-warning me-1"></i>
                                        Jejum Necessário
                                    </label>
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="descricao" class="form-label">Descrição/Observações</label>
                                <textarea class="form-control" id="descricao" name="descricao" rows="3"
                                    placeholder="Descreva o motivo da solicitação, sintomas observados, etc.">{{ old('descricao') }}</textarea>
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
                                    value="{{ old('data_exame') }}" min="{{ date('Y-m-d') }}">
                                @error('data_exame')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="hora_exame" class="form-label">Horário</label>
                                <input type="time" class="form-control" id="hora_exame" name="hora_exame"
                                    value="{{ old('hora_exame') }}">
                                @error('hora_exame')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="tempo_jejum" class="form-label">Tempo de Jejum (horas)</label>
                                <select class="form-select" id="tempo_jejum" name="tempo_jejum" disabled>
                                    <option value="">Não se aplica</option>
                                    <option value="8" {{ old('tempo_jejum') == '8' ? 'selected' : '' }}>8 horas
                                    </option>
                                    <option value="12" {{ old('tempo_jejum') == '12' ? 'selected' : '' }}>12 horas
                                    </option>
                                    <option value="14" {{ old('tempo_jejum') == '14' ? 'selected' : '' }}>14 horas
                                    </option>
                                    <option value="16" {{ old('tempo_jejum') == '16' ? 'selected' : '' }}>16 horas
                                    </option>
                                </select>
                                @error('tempo_jejum')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="laboratorio" class="form-label">Laboratório/Local</label>
                                <input type="text" class="form-control" id="laboratorio" name="laboratorio"
                                    value="{{ old('laboratorio') }}" placeholder="Nome do laboratório ou local do exame">
                                @error('laboratorio')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="local_exame" class="form-label">Endereço/Sala</label>
                                <input type="text" class="form-control" id="local_exame" name="local_exame"
                                    value="{{ old('local_exame') }}" placeholder="Endereço ou sala específica">
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
                                    placeholder="Descreva as instruções de preparo para o exame...">{{ old('preparo') }}</textarea>
                                @error('preparo')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Orientações pré-definidas -->
                            <div class="col-12">
                                <label class="form-label">Orientações Comuns</label>
                                <div class="d-flex flex-wrap gap-2">
                                    <button type="button" class="btn btn-outline-secondary btn-sm"
                                        onclick="adicionarPreparo('Jejum de 12 horas')">
                                        Jejum 12h
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm"
                                        onclick="adicionarPreparo('Suspender medicamentos por 24h')">
                                        Suspender medicamentos
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm"
                                        onclick="adicionarPreparo('Trazer exames anteriores')">
                                        Trazer exames anteriores
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm"
                                        onclick="adicionarPreparo('Beber 1 litro de água antes do exame')">
                                        Hidratação
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm"
                                        onclick="adicionarPreparo('Evitar exercícios físicos 24h antes')">
                                        Evitar exercícios
                                    </button>
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="observacoes_preparo" class="form-label">Observações Adicionais</label>
                                <textarea class="form-control" id="observacoes_preparo" name="observacoes_preparo" rows="2"
                                    placeholder="Observações especiais sobre o preparo...">{{ old('observacoes_preparo') }}</textarea>
                                @error('observacoes_preparo')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
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
                                Solicitar Exame
                            </button>
                            <button type="button" class="btn btn-outline-info" onclick="salvarRascunho()">
                                <i class="fas fa-file-alt me-2"></i>
                                Salvar Rascunho
                            </button>
                            <a href="{{ route('exames.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>
                                Cancelar
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Seleção do Paciente -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-user me-2"></i>
                            Selecionar Paciente
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="paciente_search" class="form-label required">Buscar Paciente</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="paciente_search"
                                    placeholder="Digite o nome ou CPF do paciente..." autocomplete="off">
                                <button class="btn btn-outline-secondary" type="button" onclick="buscarPaciente()">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                            <input type="hidden" id="paciente_id" name="paciente_id" value="{{ old('paciente_id') }}"
                                required>
                            @error('paciente_id')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Resultado da busca de paciente -->
                        <div id="paciente-resultado" class="d-none">
                            <div class="alert alert-success">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-lg me-3">
                                        <div class="avatar-title rounded-circle bg-primary" id="paciente-avatar"></div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold" id="paciente-nome"></div>
                                        <small class="text-muted" id="paciente-cpf"></small>
                                    </div>
                                    <button type="button" class="btn-close" onclick="limparPaciente()"></button>
                                </div>
                                <hr>
                                <div class="row text-center">
                                    <div class="col-4">
                                        <div class="small text-muted">Idade</div>
                                        <div class="fw-bold" id="paciente-idade"></div>
                                    </div>
                                    <div class="col-4">
                                        <div class="small text-muted">Sexo</div>
                                        <div class="fw-bold" id="paciente-sexo"></div>
                                    </div>
                                    <div class="col-4">
                                        <div class="small text-muted">Cartão SUS</div>
                                        <div class="fw-bold small" id="paciente-sus"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Lista de pacientes recentes -->
                        <div id="pacientes-recentes">
                            <label class="form-label">Pacientes Recentes</label>
                            <div class="list-group list-group-flush">
                                <!-- Os pacientes recentes serão carregados aqui -->
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
                                        {{ old('medico_id') == $medico->id ? 'selected' : '' }}>
                                        {{ $medico->nome }} - {{ $medico->especialidade }}
                                    </option>
                                @endforeach
                            </select>
                            @error('medico_id')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Informações do médico selecionado -->
                        <div id="medico-info" class="d-none">
                            <div class="bg-light p-3 rounded">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-lg me-3">
                                        <div class="avatar-title rounded-circle bg-success" id="medico-avatar"></div>
                                    </div>
                                    <div>
                                        <div class="fw-bold" id="medico-nome"></div>
                                        <small class="text-muted" id="medico-especialidade"></small>
                                    </div>
                                </div>
                                <hr>
                                <div class="small">
                                    <strong>CRM:</strong> <span id="medico-crm"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Consulta Relacionada (opcional) -->
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
                                <!-- As consultas serão carregadas dinamicamente -->
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
                            <button type="button" class="btn btn-outline-info" onclick="carregarModeloExame()">
                                <i class="fas fa-file-import me-2"></i>
                                Carregar Modelo
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="limparFormulario()">
                                <i class="fas fa-eraser me-2"></i>
                                Limpar Formulário
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('scripts')
    <script>
        // Exames sugeridos por categoria
        const examesPorCategoria = {
            'laboratorio': [
                'Hemograma Completo', 'Glicemia de Jejum', 'Colesterol Total', 'Triglicérides',
                'Creatinina', 'Ureia', 'TGO/TGP', 'TSH', 'T4 Livre', 'Ácido Úrico',
                'Exame de Urina', 'Parasitológico de Fezes', 'PSA', 'Beta HCG'
            ],
            'imagem': [
                'Raio-X de Tórax', 'Raio-X de Abdômen', 'Ultrassom Abdominal', 'Ultrassom Pélvico',
                'Tomografia de Crânio', 'Tomografia de Abdômen', 'Ressonância Magnética',
                'Mamografia', 'Densitometria Óssea'
            ],
            'cardiologico': [
                'Eletrocardiograma (ECG)', 'Ecocardiograma', 'Teste Ergométrico',
                'Holter 24h', 'MAPA', 'Angiotomografia Coronariana'
            ],
            'neurologia': [
                'Eletroencefalograma (EEG)', 'Ressonância de Crânio', 'Tomografia de Crânio',
                'Doppler de Carótidas', 'Eletroneuromiografia'
            ],
            'endoscopia': [
                'Endoscopia Digestiva Alta', 'Colonoscopia', 'Retossigmoidoscopia',
                'Broncoscopia', 'Cistoscopia'
            ]
        };

        document.getElementById('categoria').addEventListener('change', function() {
            const categoria = this.value;
            const divSugeridos = document.getElementById('exames-sugeridos');
            const listaSugeridos = document.getElementById('lista-exames-sugeridos');

            if (categoria && examesPorCategoria[categoria]) {
                listaSugeridos.innerHTML = '';
                examesPorCategoria[categoria].forEach(exame => {
                    const button = document.createElement('button');
                    button.type = 'button';
                    button.className = 'btn btn-outline-primary btn-sm';
                    button.textContent = exame;
                    button.onclick = () => {
                        document.getElementById('tipo_exame').value = exame;
                    };
                    listaSugeridos.appendChild(button);
                });
                divSugeridos.style.display = 'block';
            } else {
                divSugeridos.style.display = 'none';
            }
        });

        // Controle do jejum
        document.getElementById('jejum_necessario').addEventListener('change', function() {
            const tempoJejum = document.getElementById('tempo_jejum');
            tempoJejum.disabled = !this.checked;
            if (!this.checked) {
                tempoJejum.value = '';
            }
        });

        function adicionarPreparo(texto) {
            const preparoField = document.getElementById('preparo');
            const valorAtual = preparoField.value;

            if (valorAtual) {
                preparoField.value = valorAtual + '\n' + texto;
            } else {
                preparoField.value = texto;
            }
        }

        // Busca de paciente
        let timeoutBusca;
        document.getElementById('paciente_search').addEventListener('input', function() {
            clearTimeout(timeoutBusca);
            const query = this.value;

            if (query.length >= 3) {
                timeoutBusca = setTimeout(() => {
                    buscarPacienteAjax(query);
                }, 500);
            }
        });

        function buscarPacienteAjax(query) {
            fetch('/api/pacientes/buscar?q=' + encodeURIComponent(query))
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        // Mostrar resultados da busca
                        mostrarResultadosPaciente(data);
                    }
                })
                .catch(error => {
                    console.error('Erro na busca:', error);
                });
        }

        function selecionarPaciente(paciente) {
            document.getElementById('paciente_id').value = paciente.id;
            document.getElementById('paciente-nome').textContent = paciente.nome;
            document.getElementById('paciente-cpf').textContent = paciente.cpf;
            document.getElementById('paciente-avatar').textContent = paciente.nome.charAt(0);
            document.getElementById('paciente-idade').textContent = paciente.idade + ' anos';
            document.getElementById('paciente-sexo').textContent = paciente.sexo === 'M' ? 'Masculino' : 'Feminino';
            document.getElementById('paciente-sus').textContent = paciente.cartao_sus;

            document.getElementById('paciente-resultado').classList.remove('d-none');
            document.getElementById('paciente_search').value = paciente.nome;

            // Carregar consultas do paciente
            carregarConsultasPaciente(paciente.id);
        }

        function limparPaciente() {
            document.getElementById('paciente_id').value = '';
            document.getElementById('paciente-resultado').classList.add('d-none');
            document.getElementById('paciente_search').value = '';
            document.getElementById('consulta_id').innerHTML = '<option value="">Nenhuma consulta</option>';
        }

        function carregarConsultasPaciente(pacienteId) {
            fetch(`/api/pacientes/${pacienteId}/consultas`)
                .then(response => response.json())
                .then(consultas => {
                    const select = document.getElementById('consulta_id');
                    select.innerHTML = '<option value="">Nenhuma consulta</option>';

                    consultas.forEach(consulta => {
                        const option = document.createElement('option');
                        option.value = consulta.id;
                        option.textContent = `Consulta #${consulta.id} - ${consulta.data_consulta}`;
                        select.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Erro ao carregar consultas:', error);
                });
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

        function salvarRascunho() {
            // Implementar salvamento de rascunho
            alert('Funcionalidade de rascunho em desenvolvimento');
        }

        function carregarModeloExame() {
            // Implementar carregamento de modelo
            alert('Funcionalidade de modelo em desenvolvimento');
        }

        function limparFormulario() {
            if (confirm('Tem certeza que deseja limpar todo o formulário?')) {
                document.getElementById('form-exame').reset();
                limparPaciente();
                document.getElementById('medico-info').classList.add('d-none');
                document.getElementById('exames-sugeridos').style.display = 'none';
            }
        }

        // Validação do formulário
        document.getElementById('form-exame').addEventListener('submit', function(e) {
            const pacienteId = document.getElementById('paciente_id').value;
            const medicoId = document.getElementById('medico_id').value;
            const tipoExame = document.getElementById('tipo_exame').value;
            const categoria = document.getElementById('categoria').value;

            if (!pacienteId || !medicoId || !tipoExame || !categoria) {
                e.preventDefault();
                alert('Por favor, preencha todos os campos obrigatórios.');
            }
        });

        // Carregar pacientes recentes ao carregar a página
        window.addEventListener('load', function() {
            fetch('/api/pacientes/recentes')
                .then(response => response.json())
                .then(pacientes => {
                    const container = document.querySelector('#pacientes-recentes .list-group');
                    container.innerHTML = '';

                    pacientes.forEach(paciente => {
                        const item = document.createElement('a');
                        item.href = '#';
                        item.className = 'list-group-item list-group-item-action';
                        item.innerHTML = `
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm me-2">
                                <div class="avatar-title rounded-circle bg-primary">${paciente.nome.charAt(0)}</div>
                            </div>
                            <div>
                                <div class="fw-medium">${paciente.nome}</div>
                                <small class="text-muted">${paciente.cpf}</small>
                            </div>
                        </div>
                    `;
                        item.onclick = (e) => {
                            e.preventDefault();
                            selecionarPaciente(paciente);
                        };
                        container.appendChild(item);
                    });
                })
                .catch(error => {
                    console.error('Erro ao carregar pacientes recentes:', error);
                });
        });
    </script>
@endsection

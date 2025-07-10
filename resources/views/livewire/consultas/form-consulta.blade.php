<div>
    <div class="container-fluid">
        <div class="card shadow border-0">
            <div class="card-header bg-white py-3">
                <h4>{{ $modo === 'criar' ? 'Agendar Nova Consulta' : 'Editar Consulta' }}</h4>
            </div>
            <div class="card-body">
                @if ($showError)
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ $errorMessage }}
                        <button type="button" class="btn-close" wire:click="$set('showError', false)"
                            aria-label="Close"></button>
                    </div>
                @endif

                <form wire:submit="salvar">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="pacienteId" class="form-label">Paciente <span
                                    class="text-danger">*</span></label>
                            <select class="form-select @error('pacienteId') is-invalid @enderror" id="pacienteId"
                                wire:model.live="pacienteId" {{ Auth::user()->tipo === 'paciente' ? 'disabled' : '' }}>
                                <option value="" selected>Selecione o paciente</option>
                                @foreach ($pacientes as $paciente)
                                    <option value="{{ $paciente->id }}">
                                        {{ $paciente->nome }} ({{ $paciente->cpf }})
                                    </option>
                                @endforeach
                            </select>
                            @error('pacienteId')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            @if ($pacienteSelecionado)
                                <div class="mt-2 p-2 bg-light rounded">
                                    <small>
                                        <strong>Paciente selecionado:</strong> {{ $pacienteSelecionado->nome }}<br>
                                        <strong>CPF:</strong> {{ $pacienteSelecionado->cpf }}<br>
                                        <strong>Telefone:</strong> {{ $pacienteSelecionado->telefone }}
                                    </small>
                                </div>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label for="medicoId" class="form-label">Médico <span class="text-danger">*</span></label>
                            <select class="form-select @error('medicoId') is-invalid @enderror" id="medicoId"
                                wire:model.live="medicoId" {{ Auth::user()->tipo === 'medico' ? 'disabled' : '' }}>
                                <option value="" selected>Selecione o médico</option>
                                @foreach ($medicos as $medico)
                                    <option value="{{ $medico->id }}">
                                        Dr(a). {{ $medico->nome }} ({{ $medico->especialidade }})
                                    </option>
                                @endforeach
                            </select>
                            @error('medicoId')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="data" class="form-label">Data <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('data') is-invalid @enderror"
                                id="data" wire:model.live="data">

                            @error('data')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            @if ($medicoId && $data)
                                <div class="form-text mt-1">
                                    <i class="fas fa-info-circle"></i> Selecione uma data em que o médico esteja de
                                    plantão.
                                </div>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label for="hora" class="form-label">Hora <span class="text-danger">*</span></label>
                            <input type="time" class="form-control @error('hora') is-invalid @enderror"
                                id="hora" wire:model.live="hora">
                            @error('hora')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            @if ($medicoId && $data)
                                <div class="form-text mt-1">
                                    <i class="fas fa-clock"></i> Selecione um horário disponível durante o plantão do
                                    médico.
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="tipoConsulta" class="form-label">Tipo de Consulta <span
                                    class="text-danger">*</span></label>
                            <select class="form-select @error('tipoConsulta') is-invalid @enderror" id="tipoConsulta"
                                wire:model.live="tipoConsulta">
                                <option value="" selected>Selecione o tipo de consulta</option>
                                <option value="Rotina">Rotina</option>
                                <option value="Retorno">Retorno</option>
                                <option value="Urgência">Urgência</option>
                                <option value="Especializada">Especializada</option>
                            </select>
                            @error('tipoConsulta')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if ($modo === 'editar')
                            <div class="col-md-6">
                                <label for="status" class="form-label">Status <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status"
                                    wire:model.live="status">
                                    <option value="agendada">Agendada</option>
                                    <option value="confirmada">Confirmada</option>
                                    <option value="em_andamento">Em andamento</option>
                                    <option value="concluida">Concluída</option>
                                    <option value="cancelada">Cancelada</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif
                    </div>

                    <!-- Informações sobre plantões do médico -->
                    @if ($medicoId && $data)
                        <div class="mb-3">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Plantões e Horários Disponíveis</h6>
                                </div>
                                <div class="card-body">
                                    <div wire:loading wire:target="medicoId, data">
                                        <div class="text-center">
                                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                                <span class="visually-hidden">Carregando...</span>
                                            </div>
                                            <span class="ms-2">Verificando disponibilidade...</span>
                                        </div>
                                    </div>

                                    <div wire:loading.remove wire:target="medicoId, data">
                                        <!-- Plantões disponíveis -->
                                        <div id="plantoesDisponiveis">
                                            <!-- Será preenchido via JavaScript -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="queixaPrincipal" class="form-label">Queixa Principal / Motivo <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control @error('queixaPrincipal') is-invalid @enderror" id="queixaPrincipal"
                                wire:model.live="queixaPrincipal" rows="3"></textarea>
                            @error('queixaPrincipal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="observacoes" class="form-label">Observações</label>
                            <textarea class="form-control @error('observacoes') is-invalid @enderror" id="observacoes" wire:model.live="observacoes"
                                rows="3"></textarea>
                            @error('observacoes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('consultas.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i>
                            {{ $modo === 'criar' ? 'Agendar Consulta' : 'Salvar Alterações' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Indicador de carregamento -->
        <div wire:loading wire:target="salvar"
            class="position-fixed top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center bg-dark bg-opacity-50"
            style="z-index: 9999;">
            <div class="card p-4">
                <div class="text-center">
                    <div class="spinner-border text-primary mb-2" role="status">
                        <span class="visually-hidden">Carregando...</span>
                    </div>
                    <p class="mb-0">Processando, por favor aguarde...</p>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('livewire:initialized', () => {
                // Lidar com plantões carregados
                @this.on('plantoesCarregados', (data) => {
                    const plantoes = data.plantoes;
                    const plantoesDiv = document.getElementById('plantoesDisponiveis');

                    if (!plantoesDiv) return;

                    if (plantoes.length === 0) {
                        plantoesDiv.innerHTML = `
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            O médico não possui plantões disponíveis para a data selecionada.
                        </div>
                    `;
                        return;
                    }

                    let html = '<div class="mb-3"><strong>Plantões encontrados:</strong></div>';
                    html += '<div class="list-group mb-3">';

                    plantoes.forEach(plantao => {
                        const horaInicio = new Date(plantao.hora_inicio).toLocaleTimeString([], {
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                        const horaFim = new Date(plantao.hora_fim).toLocaleTimeString([], {
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                        const vagas = plantao.capacidade_consultas -
                        0; // Substituir pelo número real de consultas

                        html += `
                        <div class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Horário: ${horaInicio} às ${horaFim}</h6>
                                <span class="badge ${vagas > 0 ? 'bg-success' : 'bg-danger'}">${vagas} vagas</span>
                            </div>
                            <p class="mb-1">
                                ${plantao.recorrente ? 'Plantão recorrente (toda semana)' : 'Plantão específico para esta data'}
                            </p>
                            <button type="button" class="btn btn-sm btn-outline-primary mt-2"
                                onclick="selecionarHorario('${horaInicio}')">
                                Selecionar este horário
                            </button>
                        </div>
                    `;
                    });

                    html += '</div>';
                    plantoesDiv.innerHTML = html;

                    // Enviar horários disponíveis para o componente Livewire
                    const horarios = plantoes.map(p => {
                        return {
                            inicio: new Date(p.hora_inicio).toLocaleTimeString([], {
                                hour: '2-digit',
                                minute: '2-digit'
                            }),
                            fim: new Date(p.hora_fim).toLocaleTimeString([], {
                                hour: '2-digit',
                                minute: '2-digit'
                            }),
                            id: p.id
                        };
                    });

                    @this.dispatch('horariosAtualizados', horarios);
                });
            });

            function selecionarHorario(horario) {
                // Formatar para o formato esperado pelo input time (HH:MM)
                const [horas, minutos] = horario.split(':');
                const horaFormatada = `${horas.padStart(2, '0')}:${minutos.padStart(2, '0')}`;

                // Atualizar o campo de hora
                document.getElementById('hora').value = horaFormatada;

                // Notificar o Livewire da mudança
                window.Livewire.find(document.querySelector('[wire\\:id]').getAttribute('wire:id')).set('hora', horaFormatada);
            }
        </script>
    @endpush
</div>

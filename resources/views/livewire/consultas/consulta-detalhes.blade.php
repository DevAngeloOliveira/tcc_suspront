<div>
    <div class="container-fluid">
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <h2>Detalhes da Consulta</h2>
            <div>
                @if (in_array($consulta->status, ['agendada', 'confirmada']))
                    <a href="{{ route('consultas.edit', $consulta->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                @endif

                @if ($consulta->status === 'agendada')
                    <button wire:click="atualizarStatus('confirmada')" class="btn btn-primary ms-1">
                        <i class="fas fa-check-circle"></i> Confirmar
                    </button>
                @endif

                @if (in_array($consulta->status, ['agendada', 'confirmada']) &&
                        auth()->user()->tipo == 'medico' &&
                        auth()->user()->medico->id == $consulta->medico_id)
                    <button wire:click="atualizarStatus('em_andamento')" class="btn btn-info ms-1">
                        <i class="fas fa-play"></i> Iniciar Atendimento
                    </button>
                @endif

                @if (
                    $consulta->status === 'em_andamento' &&
                        auth()->user()->tipo == 'medico' &&
                        auth()->user()->medico->id == $consulta->medico_id)
                    <button wire:click="atualizarStatus('concluida')" class="btn btn-success ms-1">
                        <i class="fas fa-check"></i> Concluir Consulta
                    </button>
                @endif

                @if (in_array($consulta->status, ['agendada', 'confirmada']))
                    <button wire:click="confirmarAcao('cancelar', 'Tem certeza que deseja cancelar esta consulta?')"
                        class="btn btn-danger ms-1">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                @endif

                <a href="{{ route('consultas.index') }}" class="btn btn-secondary ms-1">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Dados da Consulta -->
            <div class="mb-4 col-md-6">
                <div class="card shadow border-0">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <h5 class="mb-0 fw-bold">Informações da Consulta</h5>
                        <span
                            class="badge {{ $consulta->status === 'agendada'
                                ? 'bg-warning text-dark'
                                : ($consulta->status === 'confirmada'
                                    ? 'bg-primary'
                                    : ($consulta->status === 'em_andamento'
                                        ? 'bg-info'
                                        : ($consulta->status === 'concluida'
                                            ? 'bg-success'
                                            : 'bg-danger'))) }}">
                            {{ ucfirst(str_replace('_', ' ', $consulta->status)) }}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 row">
                            <div class="col-md-6">
                                <strong>Data:</strong> {{ Carbon\Carbon::parse($consulta->data_hora)->format('d/m/Y') }}
                            </div>
                            <div class="col-md-6">
                                <strong>Hora:</strong> {{ Carbon\Carbon::parse($consulta->data_hora)->format('H:i') }}
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="col-md-12">
                                <strong>Médico:</strong> Dr(a). {{ $consulta->medico->nome }}
                                ({{ $consulta->medico->especialidade }})
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="col-md-12">
                                <strong>Tipo de Consulta:</strong> {{ $consulta->tipo_consulta }}
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="col-md-12">
                                <strong>Motivo da Consulta:</strong>
                                <p class="mt-1">{{ $consulta->queixa_principal }}</p>
                            </div>
                        </div>
                        @if ($consulta->observacoes)
                            <div class="mb-3 row">
                                <div class="col-md-12">
                                    <strong>Observações:</strong>
                                    <p class="mt-1">{{ $consulta->observacoes }}</p>
                                </div>
                            </div>
                        @endif

                        <div class="mt-4 text-muted small">
                            <p class="mb-1">
                                <span><i class="far fa-calendar-alt me-1"></i> Criado em:
                                    {{ Carbon\Carbon::parse($consulta->created_at)->format('d/m/Y H:i') }}</span>
                            </p>
                            @if ($consulta->created_at != $consulta->updated_at)
                                <p class="mb-0">
                                    <span><i class="far fa-clock me-1"></i> Atualizado em:
                                        {{ Carbon\Carbon::parse($consulta->updated_at)->format('d/m/Y H:i') }}</span>
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dados do Paciente -->
            <div class="mb-4 col-md-6">
                <div class="card shadow border-0">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold">Dados do Paciente</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 row">
                            <div class="col-md-6">
                                <strong>Nome:</strong> {{ $consulta->paciente->nome }}
                            </div>
                            <div class="col-md-6">
                                <strong>CPF:</strong> {{ $consulta->paciente->cpf }}
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="col-md-6">
                                <strong>Data de Nascimento:</strong>
                                {{ Carbon\Carbon::parse($consulta->paciente->data_nascimento)->format('d/m/Y') }}
                            </div>
                            <div class="col-md-6">
                                <strong>Idade:</strong>
                                {{ Carbon\Carbon::parse($consulta->paciente->data_nascimento)->age }} anos
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="col-md-6">
                                <strong>Sexo:</strong>
                                {{ $consulta->paciente->sexo == 'M' ? 'Masculino' : 'Feminino' }}
                            </div>
                            <div class="col-md-6">
                                <strong>Telefone:</strong> {{ $consulta->paciente->telefone }}
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="col-md-12">
                                <strong>Email:</strong> {{ $consulta->paciente->email ?? 'Não informado' }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <strong>Endereço:</strong> {{ $consulta->paciente->endereco ?? 'Não informado' }}
                            </div>
                        </div>

                        <div class="mt-3">
                            <a href="{{ route('pacientes.show', $consulta->paciente->id) }}"
                                class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-user"></i> Ver Perfil Completo
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Prontuário e Evolução -->
        @if ($consulta->status === 'em_andamento' || $consulta->status === 'concluida')
            <div class="row">
                <div class="mb-4 col-12">
                    <div class="card shadow border-0">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                            <h5 class="mb-0 fw-bold">Evolução da Consulta</h5>

                            @if (
                                $consulta->status === 'em_andamento' &&
                                    auth()->user()->tipo == 'medico' &&
                                    auth()->user()->medico->id == $consulta->medico_id)
                                <button wire:click="toggleFormularioEvolucao" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-plus"></i> Adicionar Evolução
                                </button>
                            @endif
                        </div>
                        <div class="card-body">
                            @if ($mostrarFormularioEvolucao)
                                <form wire:submit="salvarEvolucao" class="mb-4 p-3 bg-light rounded">
                                    <div class="mb-3">
                                        <label for="evolucao" class="form-label">Nova Evolução</label>
                                        <textarea wire:model.live="evolucaoText" class="form-control @error('evolucaoText') is-invalid @enderror" id="evolucao"
                                            rows="3" placeholder="Descreva a evolução do paciente..."></textarea>
                                        @error('evolucaoText')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <button type="button" class="btn btn-outline-secondary me-2"
                                            wire:click="toggleFormularioEvolucao">Cancelar</button>
                                        <button type="submit" class="btn btn-primary">Salvar Evolução</button>
                                    </div>
                                </form>
                            @endif

                            @if ($prontuario && $prontuario->evolucao)
                                <div class="p-3 bg-light rounded">
                                    <p class="text-muted mb-2"><i class="far fa-clipboard"></i> Histórico de Evolução:
                                    </p>
                                    <div class="evolucao-text">
                                        {!! nl2br(e($prontuario->evolucao)) !!}
                                    </div>
                                </div>
                            @else
                                <div class="text-center p-4">
                                    <p class="text-muted">Nenhuma evolução registrada.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Exames -->
            <div class="row">
                <div class="mb-4 col-12">
                    <div class="card shadow border-0">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                            <h5 class="mb-0 fw-bold">Exames Solicitados</h5>

                            @if (
                                $consulta->status === 'em_andamento' &&
                                    auth()->user()->tipo == 'medico' &&
                                    auth()->user()->medico->id == $consulta->medico_id)
                                <button wire:click="toggleFormularioExame" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-plus"></i> Adicionar Exame
                                </button>
                            @endif
                        </div>
                        <div class="card-body">
                            @if ($mostrarFormularioExame)
                                <form wire:submit="salvarExame" class="mb-4 p-3 bg-light rounded">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="tipo_exame" class="form-label">Tipo de Exame</label>
                                            <select wire:model.live="novoExame.tipo"
                                                class="form-select @error('novoExame.tipo') is-invalid @enderror"
                                                id="tipo_exame">
                                                <option value="">Selecione o tipo</option>
                                                <option value="Laboratorial">Laboratorial</option>
                                                <option value="Imagem">Imagem</option>
                                                <option value="Cardiológico">Cardiológico</option>
                                                <option value="Neurológico">Neurológico</option>
                                                <option value="Outro">Outro</option>
                                            </select>
                                            @error('novoExame.tipo')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="data_solicitacao" class="form-label">Data de
                                                Solicitação</label>
                                            <input type="date" wire:model.live="novoExame.data_solicitacao"
                                                class="form-control @error('novoExame.data_solicitacao') is-invalid @enderror"
                                                id="data_solicitacao">
                                            @error('novoExame.data_solicitacao')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="descricao_exame" class="form-label">Descrição do Exame</label>
                                        <textarea wire:model.live="novoExame.descricao" class="form-control @error('novoExame.descricao') is-invalid @enderror"
                                            id="descricao_exame" rows="2" placeholder="Descreva o exame a ser realizado..."></textarea>
                                        @error('novoExame.descricao')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="observacoes_exame" class="form-label">Observações</label>
                                        <textarea wire:model.live="novoExame.observacoes" class="form-control" id="observacoes_exame" rows="2"
                                            placeholder="Observações sobre o exame (opcional)..."></textarea>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <button type="button" class="btn btn-outline-secondary me-2"
                                            wire:click="toggleFormularioExame">Cancelar</button>
                                        <button type="submit" class="btn btn-primary">Solicitar Exame</button>
                                    </div>
                                </form>
                            @endif

                            @if (count($exames) > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Tipo</th>
                                                <th>Descrição</th>
                                                <th>Solicitado em</th>
                                                <th>Status</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($exames as $exame)
                                                <tr>
                                                    <td>{{ $exame->tipo }}</td>
                                                    <td>{{ \Illuminate\Support\Str::limit($exame->descricao, 50) }}
                                                    </td>
                                                    <td>{{ Carbon\Carbon::parse($exame->data_solicitacao)->format('d/m/Y') }}
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge {{ $exame->status === 'pendente' ? 'bg-warning text-dark' : 'bg-success' }}">
                                                            {{ ucfirst($exame->status) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('exames.show', $exame->id) }}"
                                                            class="btn btn-sm btn-outline-info">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center p-4">
                                    <p class="text-muted">Nenhum exame solicitado.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Receitas -->
            <div class="row">
                <div class="mb-4 col-12">
                    <div class="card shadow border-0">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                            <h5 class="mb-0 fw-bold">Receitas</h5>

                            @if (
                                ($consulta->status === 'em_andamento' || $consulta->status === 'concluida') &&
                                    auth()->user()->tipo === 'medico' &&
                                    auth()->user()->medico->id === $consulta->medico_id)
                                <a href="{{ route('receitas.create', ['consulta_id' => $consulta->id]) }}"
                                    class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus"></i> Nova Receita
                                </a>
                            @endif
                        </div>
                        <div class="card-body">
                            @if ($consulta->receitas && $consulta->receitas->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Data</th>
                                                <th>Medicamentos</th>
                                                <th>Validade</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($consulta->receitas as $receita)
                                                <tr>
                                                    <td>{{ $receita->created_at->format('d/m/Y') }}</td>
                                                    <td>
                                                        <span class="d-inline-block text-truncate"
                                                            style="max-width: 250px;">
                                                            {{ $receita->medicamentos }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @php
                                                            $today = \Carbon\Carbon::today();
                                                            $expiryDate = \Carbon\Carbon::parse($receita->validade);
                                                            $isExpired = $expiryDate->lt($today);
                                                            $isAboutToExpire =
                                                                !$isExpired && $expiryDate->diffInDays($today) <= 7;
                                                        @endphp
                                                        <span
                                                            class="badge {{ $isExpired ? 'bg-danger' : ($isAboutToExpire ? 'bg-warning' : 'bg-success') }}">
                                                            {{ $receita->validade->format('d/m/Y') }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('receitas.show', $receita->id) }}"
                                                            class="btn btn-sm btn-outline-info" title="Ver detalhes">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('receitas.imprimir', $receita->id) }}"
                                                            class="btn btn-sm btn-outline-primary" target="_blank"
                                                            title="Imprimir">
                                                            <i class="fas fa-print"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center p-4">
                                    <p class="text-muted">Nenhuma receita emitida.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Modal de confirmação -->
        @if ($confirmandoAcao)
            <div class="modal fade show" style="display: block; background-color: rgba(0, 0, 0, 0.5);"
                tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Confirmação</h5>
                            <button type="button" class="btn-close" wire:click="cancelarConfirmacao"></button>
                        </div>
                        <div class="modal-body">
                            <p>{{ $mensagemConfirmacao }}</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                wire:click="cancelarConfirmacao">Cancelar</button>
                            <button type="button" class="btn btn-danger"
                                wire:click="executarAcaoConfirmada">Confirmar</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Livewire.on('notify', function(data) {
                    const type = data.type;
                    const message = data.message;

                    // Usar biblioteca de notificação ou mostrar alerta simples
                    alert(message);
                });
            });
        </script>
    </div>
</div>

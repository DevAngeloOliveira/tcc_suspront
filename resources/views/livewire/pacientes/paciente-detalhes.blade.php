<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>Paciente: {{ $paciente->nome }}</h2>
            <p class="text-muted">
                Cartão SUS: {{ $paciente->cartao_sus }} |
                CPF: {{ $paciente->cpf }}
            </p>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('pacientes.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
            <a href="{{ route('pacientes.edit', $paciente->id) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Editar
            </a>
            <a href="{{ route('consultas.create', ['paciente_id' => $paciente->id]) }}" class="btn btn-primary">
                <i class="fas fa-calendar-plus"></i> Nova Consulta
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $tabAtiva === 'info' ? 'active' : '' }}" wire:click="setTab('info')"
                        type="button">
                        <i class="fas fa-user"></i> Informações Pessoais
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $tabAtiva === 'prontuario' ? 'active' : '' }}"
                        wire:click="setTab('prontuario')" type="button">
                        <i class="fas fa-file-medical"></i> Prontuário
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $tabAtiva === 'historico' ? 'active' : '' }}"
                        wire:click="setTab('historico')" type="button">
                        <i class="fas fa-history"></i> Histórico
                    </button>
                </li>
            </ul>
        </div>

        <div class="card-body" wire:loading.class="opacity-50">
            <div wire:loading class="text-center py-3">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Carregando...</span>
                </div>
                <p class="mt-2">Carregando dados...</p>
            </div>

            <!-- Informações Pessoais -->
            <div class="{{ $tabAtiva === 'info' ? '' : 'd-none' }}">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Dados Pessoais</h5>
                        <table class="table table-striped">
                            <tr>
                                <th>Nome Completo:</th>
                                <td>{{ $paciente->nome }}</td>
                            </tr>
                            <tr>
                                <th>Data de Nascimento:</th>
                                <td>{{ \Carbon\Carbon::parse($paciente->data_nascimento)->format('d/m/Y') }}
                                    ({{ \Carbon\Carbon::parse($paciente->data_nascimento)->age }} anos)
                                </td>
                            </tr>
                            <tr>
                                <th>Sexo:</th>
                                <td>{{ $paciente->sexo === 'M' ? 'Masculino' : 'Feminino' }}</td>
                            </tr>
                            <tr>
                                <th>CPF:</th>
                                <td>{{ $paciente->cpf }}</td>
                            </tr>
                            <tr>
                                <th>RG:</th>
                                <td>{{ $paciente->rg ?: 'Não informado' }}</td>
                            </tr>
                            <tr>
                                <th>Cartão SUS:</th>
                                <td>{{ $paciente->cartao_sus }}</td>
                            </tr>
                        </table>
                    </div>

                    <div class="col-md-6">
                        <h5>Contato</h5>
                        <table class="table table-striped">
                            <tr>
                                <th>Endereço:</th>
                                <td>{{ $paciente->endereco ?: 'Não informado' }}</td>
                            </tr>
                            <tr>
                                <th>Telefone:</th>
                                <td>{{ $paciente->telefone ?: 'Não informado' }}</td>
                            </tr>
                            <tr>
                                <th>E-mail:</th>
                                <td>{{ $paciente->email ?: 'Não informado' }}</td>
                            </tr>
                        </table>

                        <h5 class="mt-4">Informações Médicas</h5>
                        <table class="table table-striped">
                            <tr>
                                <th>Alergias:</th>
                                <td>{{ $paciente->alergias ?: 'Nenhuma alergia registrada' }}</td>
                            </tr>
                            <tr>
                                <th>Condições Preexistentes:</th>
                                <td>{{ $paciente->condicoes_preexistentes ?: 'Nenhuma condição registrada' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Prontuário -->
            <div class="{{ $tabAtiva === 'prontuario' ? '' : 'd-none' }}">
                @if ($paciente->prontuario)
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> O prontuário contém o histórico médico completo do paciente.
                    </div>

                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Prontuário #{{ $paciente->prontuario->id }}</h5>
                        </div>
                        <div class="card-body">
                            @if ($paciente->prontuario->evolucoes && $paciente->prontuario->evolucoes->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th width="20%">Data</th>
                                                <th width="20%">Médico</th>
                                                <th>Evolução</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($paciente->prontuario->evolucoes as $evolucao)
                                                <tr>
                                                    <td>{{ $evolucao->created_at->format('d/m/Y H:i') }}</td>
                                                    <td>{{ $evolucao->medico->nome ?? 'N/A' }}</td>
                                                    <td>{{ $evolucao->descricao }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-center">Nenhuma evolução registrada no prontuário.</p>
                            @endif

                            <!-- Formulário para nova evolução -->
                            <div class="mt-4">
                                <h5 class="mb-3">Adicionar Nova Evolução</h5>

                                @if (session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        {{ session('success') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                @endif

                                <form wire:submit="salvarEvolucao">
                                    <div class="mb-3">
                                        <label for="medicoId" class="form-label">Médico</label>
                                        <select wire:model.live="medicoId" id="medicoId"
                                            class="form-select @error('medicoId') is-invalid @enderror" required>
                                            <option value="">Selecione o médico</option>
                                            @foreach (\App\Models\Medico::orderBy('nome')->get() as $medico)
                                                <option value="{{ $medico->id }}">{{ $medico->nome }}
                                                    ({{ $medico->especialidade }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('medicoId')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="novaEvolucao" class="form-label">Descrição da Evolução</label>
                                        <textarea wire:model.live="novaEvolucao" id="novaEvolucao" class="form-control @error('novaEvolucao') is-invalid @enderror"
                                            rows="4" required></textarea>
                                        @error('novaEvolucao')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="text-end">
                                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                            <span wire:loading.remove wire:target="salvarEvolucao">Salvar
                                                Evolução</span>
                                            <span wire:loading wire:target="salvarEvolucao">Salvando...</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> Este paciente não possui um prontuário ativo.
                    </div>
                @endif
            </div>

            <!-- Histórico -->
            <div class="{{ $tabAtiva === 'historico' ? '' : 'd-none' }}">
                <div class="mb-3">
                    <div class="btn-group" role="group">
                        <button type="button"
                            class="btn {{ $tipoLista === 'consultas' ? 'btn-primary' : 'btn-outline-primary' }}"
                            wire:click="setTipoLista('consultas')">
                            <i class="fas fa-calendar-check"></i> Consultas
                        </button>
                        <button type="button"
                            class="btn {{ $tipoLista === 'receitas' ? 'btn-primary' : 'btn-outline-primary' }}"
                            wire:click="setTipoLista('receitas')">
                            <i class="fas fa-prescription"></i> Receitas
                        </button>
                        <button type="button"
                            class="btn {{ $tipoLista === 'exames' ? 'btn-primary' : 'btn-outline-primary' }}"
                            wire:click="setTipoLista('exames')">
                            <i class="fas fa-vial"></i> Exames
                        </button>
                    </div>
                </div>

                <!-- Lista de Consultas -->
                @if ($tipoLista === 'consultas')
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Histórico de Consultas</h5>
                        </div>
                        <div class="card-body">
                            @if ($consultas && $consultas->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Data</th>
                                                <th>Médico</th>
                                                <th>Especialidade</th>
                                                <th>Status</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($consultas as $consulta)
                                                <tr>
                                                    <td>{{ \Carbon\Carbon::parse($consulta->data_hora)->format('d/m/Y H:i') }}
                                                    </td>
                                                    <td>{{ $consulta->medico->nome }}</td>
                                                    <td>{{ $consulta->medico->especialidade }}</td>
                                                    <td>
                                                        <span
                                                            class="badge {{ $consulta->status === 'agendada'
                                                                ? 'bg-info'
                                                                : ($consulta->status === 'confirmada'
                                                                    ? 'bg-primary'
                                                                    : ($consulta->status === 'realizada'
                                                                        ? 'bg-success'
                                                                        : ($consulta->status === 'cancelada'
                                                                            ? 'bg-danger'
                                                                            : 'bg-secondary'))) }}">
                                                            {{ ucfirst($consulta->status) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('consultas.show', $consulta->id) }}"
                                                            class="btn btn-sm btn-info" title="Ver detalhes">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex justify-content-center mt-3">
                                    {{ $consultas->links() }}
                                </div>
                            @else
                                <p class="text-center">Nenhuma consulta registrada para este paciente.</p>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Lista de Receitas -->
                @if ($tipoLista === 'receitas')
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Histórico de Receitas</h5>
                        </div>
                        <div class="card-body">
                            <livewire:receitas.receitas-list :pacienteId="$paciente->id" />
                        </div>
                    </div>
                @endif

                <!-- Lista de Exames -->
                @if ($tipoLista === 'exames')
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Histórico de Exames</h5>
                        </div>
                        <div class="card-body">
                            @if ($exames && $exames->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Data Solicitação</th>
                                                <th>Tipo de Exame</th>
                                                <th>Médico Solicitante</th>
                                                <th>Status</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($exames as $exame)
                                                <tr>
                                                    <td>{{ \Carbon\Carbon::parse($exame->data_solicitacao)->format('d/m/Y') }}
                                                    </td>
                                                    <td>{{ $exame->tipo }}</td>
                                                    <td>{{ $exame->medico->nome }}</td>
                                                    <td>
                                                        <span
                                                            class="badge {{ $exame->status === 'solicitado'
                                                                ? 'bg-info'
                                                                : ($exame->status === 'agendado'
                                                                    ? 'bg-primary'
                                                                    : ($exame->status === 'realizado'
                                                                        ? 'bg-success'
                                                                        : ($exame->status === 'cancelado'
                                                                            ? 'bg-danger'
                                                                            : 'bg-secondary'))) }}">
                                                            {{ ucfirst($exame->status) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('exames.show', $exame->id) }}"
                                                            class="btn btn-sm btn-info" title="Ver detalhes">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex justify-content-center mt-3">
                                    {{ $exames->links() }}
                                </div>
                            @else
                                <p class="text-center">Nenhum exame registrado para este paciente.</p>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

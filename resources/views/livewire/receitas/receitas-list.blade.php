<div>
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Receitas Médicas</h5>
                <div>
                    @if ($paciente_id)
                        <a href="{{ route('receitas.create', ['paciente_id' => $paciente_id]) }}"
                            class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Nova Receita
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($receitas->count() > 0)
                <div class="table-responsive" wire:loading.class="opacity-50">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Médico</th>
                                <th>Medicamentos</th>
                                <th>Validade</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($receitas as $receita)
                                <tr>
                                    <td>{{ $receita->created_at->format('d/m/Y') }}</td>
                                    <td>{{ $receita->medico->nome ?? 'N/A' }}</td>
                                    <td>
                                        <span class="d-inline-block text-truncate" style="max-width: 250px;">
                                            {{ $receita->medicamentos }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $today = \Carbon\Carbon::today();
                                            $expiryDate = \Carbon\Carbon::parse($receita->validade);
                                            $isExpired = $expiryDate->lt($today);
                                        @endphp
                                        <span class="badge {{ $isExpired ? 'bg-danger' : 'bg-success' }}">
                                            {{ $expiryDate->format('d/m/Y') }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button class="btn btn-info"
                                                wire:click="visualizarReceita({{ $receita->id }})" title="Visualizar">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <a href="{{ route('receitas.edit', $receita->id) }}" class="btn btn-warning"
                                                title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('receitas.print', $receita->id) }}" target="_blank"
                                                class="btn btn-secondary" title="Imprimir">
                                                <i class="fas fa-print"></i>
                                            </a>
                                            <button class="btn btn-danger"
                                                wire:click="confirmarExclusao({{ $receita->id }})" title="Excluir">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {{ $receitas->links() }}
                </div>
            @else
                <div class="text-center py-3">
                    <p class="text-muted mb-0">Nenhuma receita encontrada</p>
                </div>
            @endif

            <div wire:loading class="text-center py-3">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Carregando...</span>
                </div>
                <p class="mt-2">Carregando receitas...</p>
            </div>
        </div>
    </div>

    <!-- Modal para visualizar receita -->
    @if ($modalReceita)
        <div class="modal fade show" id="receitaModal" tabindex="-1" aria-labelledby="receitaModalLabel"
            style="display: block; background-color: rgba(0,0,0,0.5);" aria-modal="true" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="receitaModalLabel">Receita Médica</h5>
                        <button type="button" class="btn-close" wire:click="fecharModalReceita"></button>
                    </div>
                    <div class="modal-body">
                        <div class="border p-3 mb-3">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Paciente:</strong>
                                        {{ $modalReceita->prontuario->paciente->nome ?? ($modalReceita->consulta->paciente->nome ?? 'N/A') }}
                                    </p>
                                    @if (isset($modalReceita->prontuario->paciente->data_nascimento) ||
                                            isset($modalReceita->consulta->paciente->data_nascimento))
                                        <p class="mb-1"><strong>Data Nasc.:</strong>
                                            {{ isset($modalReceita->prontuario->paciente->data_nascimento)
                                                ? \Carbon\Carbon::parse($modalReceita->prontuario->paciente->data_nascimento)->format('d/m/Y')
                                                : \Carbon\Carbon::parse($modalReceita->consulta->paciente->data_nascimento)->format('d/m/Y') }}
                                        </p>
                                    @endif
                                    <p class="mb-1"><strong>Data da Receita:</strong>
                                        {{ $modalReceita->created_at->format('d/m/Y') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Médico:</strong>
                                        {{ $modalReceita->medico->nome ?? 'N/A' }}</p>
                                    <p class="mb-1"><strong>CRM:</strong> {{ $modalReceita->medico->crm ?? 'N/A' }}
                                    </p>
                                    <p class="mb-1"><strong>Especialidade:</strong>
                                        {{ $modalReceita->medico->especialidade ?? 'N/A' }}</p>
                                </div>
                            </div>

                            @if ($modalReceita->descricao)
                                <div class="mb-3">
                                    <h6>Diagnóstico:</h6>
                                    <p class="border-bottom pb-2">{{ $modalReceita->descricao }}</p>
                                </div>
                            @endif

                            <div class="mb-3">
                                <h6>Medicamentos:</h6>
                                <p class="border-bottom pb-2" style="white-space: pre-line">
                                    {{ $modalReceita->medicamentos }}</p>
                            </div>

                            <div class="mb-3">
                                <h6>Posologia:</h6>
                                <p class="border-bottom pb-2" style="white-space: pre-line">
                                    {{ $modalReceita->posologia }}</p>
                            </div>

                            @if ($modalReceita->observacoes)
                                <div class="mb-3">
                                    <h6>Observações:</h6>
                                    <p class="border-bottom pb-2">{{ $modalReceita->observacoes }}</p>
                                </div>
                            @endif

                            <div>
                                <p class="mb-0">
                                    <strong>Validade:</strong>
                                    @php
                                        $today = \Carbon\Carbon::today();
                                        $expiryDate = \Carbon\Carbon::parse($modalReceita->validade);
                                        $isExpired = $expiryDate->lt($today);
                                    @endphp
                                    <span class="badge {{ $isExpired ? 'bg-danger' : 'bg-success' }}">
                                        {{ $expiryDate->format('d/m/Y') }}
                                        {{ $isExpired ? '(Vencida)' : '' }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="{{ route('receitas.print', $modalReceita->id) }}" target="_blank"
                            class="btn btn-primary">
                            <i class="fas fa-print"></i> Imprimir
                        </a>
                        <a href="{{ route('receitas.edit', $modalReceita->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <button type="button" class="btn btn-secondary" wire:click="fecharModalReceita">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal para confirmação de exclusão -->
    @if ($showDeleteModal)
        <div class="modal fade show" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel"
            style="display: block; background-color: rgba(0,0,0,0.5);" aria-modal="true" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Confirmar Exclusão</h5>
                        <button type="button" class="btn-close" wire:click="cancelarExclusao"></button>
                    </div>
                    <div class="modal-body">
                        <p>Tem certeza que deseja excluir esta receita?</p>
                        <p class="text-danger"><small>Esta ação não pode ser desfeita.</small></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            wire:click="cancelarExclusao">Cancelar</button>
                        <button type="button" class="btn btn-danger" wire:click="excluirReceita">
                            <span wire:loading.remove wire:target="excluirReceita">Excluir</span>
                            <span wire:loading wire:target="excluirReceita">Excluindo...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

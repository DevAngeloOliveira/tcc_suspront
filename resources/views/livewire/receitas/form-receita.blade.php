<div>
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">{{ $isEdit ? 'Editar Receita' : 'Nova Receita' }}</h5>
        </div>
        <div class="card-body">
            @if ($showSuccess)
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ $successMessage }}
                    <button type="button" class="btn-close" wire:click="$set('showSuccess', false)"
                        aria-label="Close"></button>
                </div>
            @endif

            <form wire:submit="salvar" class="mb-4">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Paciente</label>
                            <input type="text" class="form-control"
                                value="{{ $paciente->nome ?? 'Paciente não selecionado' }}" disabled>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="medico_id" class="form-label">Médico Responsável <span
                                    class="text-danger">*</span></label>
                            <select wire:model.live="medico_id" id="medico_id"
                                class="form-select @error('medico_id') is-invalid @enderror" required>
                                <option value="">Selecione o médico</option>
                                @foreach ($medicos as $medico)
                                    <option value="{{ $medico->id }}">{{ $medico->nome }}
                                        ({{ $medico->especialidade }})</option>
                                @endforeach
                            </select>
                            @error('medico_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="descricao" class="form-label">Descrição/Diagnóstico</label>
                    <textarea wire:model.live="descricao" id="descricao" class="form-control @error('descricao') is-invalid @enderror"
                        rows="2"></textarea>
                    @error('descricao')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="medicamentos" class="form-label">Medicamentos <span class="text-danger">*</span></label>
                    <textarea wire:model.live="medicamentos" id="medicamentos" class="form-control @error('medicamentos') is-invalid @enderror"
                        rows="3" required></textarea>
                    <small class="form-text text-muted">Informe o nome dos medicamentos, concentração, forma
                        farmacêutica</small>
                    @error('medicamentos')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="posologia" class="form-label">Posologia <span class="text-danger">*</span></label>
                    <textarea wire:model.live="posologia" id="posologia" class="form-control @error('posologia') is-invalid @enderror"
                        rows="3" required></textarea>
                    <small class="form-text text-muted">Dose, frequência, duração do tratamento</small>
                    @error('posologia')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="observacoes" class="form-label">Observações</label>
                    <textarea wire:model.live="observacoes" id="observacoes" class="form-control @error('observacoes') is-invalid @enderror"
                        rows="2"></textarea>
                    <small class="form-text text-muted">Instruções adicionais, cuidados, restrições</small>
                    @error('observacoes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="validade" class="form-label">Validade da Receita <span
                            class="text-danger">*</span></label>
                    <input type="date" wire:model.live="validade" id="validade"
                        class="form-control @error('validade') is-invalid @enderror" required>
                    @error('validade')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        @if ($consulta_id)
                            <a href="{{ route('consultas.show', $consulta_id) }}" class="btn btn-secondary">Voltar para
                                Consulta</a>
                        @elseif($paciente_id)
                            <a href="{{ route('pacientes.show', $paciente_id) }}" class="btn btn-secondary">Voltar para
                                Paciente</a>
                        @else
                            <button type="button" onclick="history.back();" class="btn btn-secondary">Voltar</button>
                        @endif
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                            <span wire:loading.remove
                                wire:target="salvar">{{ $isEdit ? 'Atualizar Receita' : 'Emitir Receita' }}</span>
                            <span wire:loading wire:target="salvar">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Salvando...
                            </span>
                        </button>
                        @if (!$isEdit)
                            <button type="button" class="btn btn-outline-success ms-1" id="printPreviewBtn"
                                wire:click="salvar">
                                <i class="fas fa-print"></i> Salvar e Imprimir
                            </button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if ($isEdit)
        <div class="mt-4 d-flex justify-content-center">
            <button type="button" class="btn btn-outline-primary" id="printPreviewBtn">
                <i class="fas fa-print"></i> Visualizar Impressão
            </button>
        </div>
    @endif
</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            // Adicionar evento ao botão de impressão
            document.getElementById('printPreviewBtn')?.addEventListener('click', function() {
                window.livewire.dispatch('receitaSalva').then(() => {
                    // Após salvar, abre uma prévia de impressão
                    setTimeout(() => {
                        window.open(
                            "{{ route('receitas.print', ['id' => $receita_id ?? 0]) }}",
                            '_blank');
                    }, 500);
                });
            });
        });
    </script>
@endpush

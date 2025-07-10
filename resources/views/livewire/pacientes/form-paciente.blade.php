<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4>{{ $isEdit ? 'Editar Paciente' : 'Cadastrar Paciente' }}</h4>
        </div>
        <div class="card-body">
            @if (session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form wire:submit="salvar">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="nome" class="form-label">Nome Completo <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nome') is-invalid @enderror" id="nome"
                            wire:model.live="nome" required>
                        @error('nome')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="cpf" class="form-label">CPF <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('cpf') is-invalid @enderror" id="cpf"
                            wire:model.live="cpf" placeholder="000.000.000-00" required>
                        @error('cpf')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="rg" class="form-label">RG</label>
                        <input type="text" class="form-control @error('rg') is-invalid @enderror" id="rg"
                            wire:model.live="rg">
                        @error('rg')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="cartao_sus" class="form-label">Cartão SUS <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('cartao_sus') is-invalid @enderror"
                            id="cartao_sus" wire:model.live="cartao_sus" required>
                        @error('cartao_sus')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="data_nascimento" class="form-label">Data de Nascimento <span
                                class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('data_nascimento') is-invalid @enderror"
                            id="data_nascimento" wire:model.live="data_nascimento" required>
                        @error('data_nascimento')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="sexo" class="form-label">Sexo <span class="text-danger">*</span></label>
                        <select class="form-select @error('sexo') is-invalid @enderror" id="sexo" wire:model.live="sexo"
                            required>
                            <option value="" selected disabled>Selecione...</option>
                            <option value="M">Masculino</option>
                            <option value="F">Feminino</option>
                        </select>
                        @error('sexo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="endereco" class="form-label">Endereço</label>
                        <input type="text" class="form-control @error('endereco') is-invalid @enderror"
                            id="endereco" wire:model.live="endereco">
                        @error('endereco')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="telefone" class="form-label">Telefone</label>
                        <input type="text" class="form-control @error('telefone') is-invalid @enderror"
                            id="telefone" wire:model.live="telefone" placeholder="(00) 00000-0000">
                        @error('telefone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                            wire:model.live="email">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="alergias" class="form-label">Alergias</label>
                        <textarea class="form-control @error('alergias') is-invalid @enderror" id="alergias" wire:model.live="alergias"
                            rows="3"></textarea>
                        @error('alergias')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="condicoes_preexistentes" class="form-label">Condições Preexistentes</label>
                        <textarea class="form-control @error('condicoes_preexistentes') is-invalid @enderror" id="condicoes_preexistentes"
                            wire:model.live="condicoes_preexistentes" rows="3"></textarea>
                        @error('condicoes_preexistentes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end" wire:loading.class="opacity-50">
                    <a href="{{ route('pacientes.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                    <button type="submit" class="btn btn-success" wire:loading.attr="disabled">
                        <span wire:loading.remove><i class="fas fa-save"></i> Salvar</span>
                        <span wire:loading><i class="fas fa-spinner fa-spin"></i> Salvando...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            // Máscaras para formatação
            const applyCpfMask = function(value) {
                if (!value) return '';
                value = value.replace(/\D/g, '');
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
                return value;
            };

            const applyPhoneMask = function(value) {
                if (!value) return '';
                value = value.replace(/\D/g, '');
                if (value.length > 10) {
                    value = value.replace(/^(\d{2})(\d{5})(\d{4}).*/, '($1) $2-$3');
                } else if (value.length > 5) {
                    value = value.replace(/^(\d{2})(\d{4})(\d{0,4}).*/, '($1) $2-$3');
                } else if (value.length > 2) {
                    value = value.replace(/^(\d{2})(\d{0,5})/, '($1) $2');
                } else {
                    value = value.replace(/^(\d{0,2})/, '($1');
                }
                return value;
            };

            // CPF mask
            const cpfInput = document.getElementById('cpf');
            if (cpfInput) {
                cpfInput.addEventListener('input', function() {
                    const maskedValue = applyCpfMask(this.value);
                    if (maskedValue !== this.value) {
                        this.value = maskedValue;
                        this.dispatchEvent(new Event('input'));
                    }
                    @this.cpf = maskedValue;
                });
            }

            // Telefone mask
            const telefoneInput = document.getElementById('telefone');
            if (telefoneInput) {
                telefoneInput.addEventListener('input', function() {
                    const maskedValue = applyPhoneMask(this.value);
                    if (maskedValue !== this.value) {
                        this.value = maskedValue;
                        this.dispatchEvent(new Event('input'));
                    }
                    @this.telefone = maskedValue;
                });
            }
        });
    </script>
@endpush

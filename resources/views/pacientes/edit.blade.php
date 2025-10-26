@extends('layouts.modern')

@section('title', 'Editar Paciente')

@section('breadcrumb')
    <i class="fas fa-home"></i>
    <span>Dashboard</span>
    <i class="fas fa-chevron-right"></i>
    <a href="{{ route('pacientes.index') }}">Pacientes</a>
    <i class="fas fa-chevron-right"></i>
    <a href="{{ route('pacientes.show', $paciente) }}">{{ $paciente->nome }}</a>
    <i class="fas fa-chevron-right"></i>
    <span>Editar</span>
@endsection

@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">
                    <i class="fas fa-user-edit me-2"></i>
                    Editar Paciente
                </h1>
                <p class="page-subtitle">Atualize as informações do paciente {{ $paciente->nome }}</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('pacientes.show', $paciente) }}" class="btn btn-outline-info">
                    <i class="fas fa-eye me-2"></i>
                    Visualizar
                </a>
                <a href="{{ route('pacientes.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Voltar
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('pacientes.update', $paciente) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <div class="col-lg-8">
                <!-- Dados Pessoais -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-user me-2"></i>
                            Dados Pessoais
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label for="nome" class="form-label">Nome Completo *</label>
                                <input type="text" class="form-control @error('nome') is-invalid @enderror"
                                    id="nome" name="nome" value="{{ old('nome', $paciente->nome) }}" required
                                    autofocus>
                                @error('nome')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="cpf" class="form-label">CPF *</label>
                                <input type="text" class="form-control @error('cpf') is-invalid @enderror" id="cpf"
                                    name="cpf" value="{{ old('cpf', $paciente->cpf) }}" placeholder="000.000.000-00"
                                    required>
                                @error('cpf')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="cartao_sus" class="form-label">Cartão SUS *</label>
                                <input type="text" class="form-control @error('cartao_sus') is-invalid @enderror"
                                    id="cartao_sus" name="cartao_sus"
                                    value="{{ old('cartao_sus', $paciente->cartao_sus) }}" placeholder="000 0000 0000 0000"
                                    required>
                                @error('cartao_sus')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="data_nascimento" class="form-label">Data de Nascimento *</label>
                                <input type="date" class="form-control @error('data_nascimento') is-invalid @enderror"
                                    id="data_nascimento" name="data_nascimento"
                                    value="{{ old('data_nascimento', $paciente->data_nascimento->format('Y-m-d')) }}"
                                    required>
                                @error('data_nascimento')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="sexo" class="form-label">Sexo *</label>
                                <select class="form-select @error('sexo') is-invalid @enderror" id="sexo"
                                    name="sexo" required>
                                    <option value="">Selecione...</option>
                                    <option value="M" {{ old('sexo', $paciente->sexo) == 'M' ? 'selected' : '' }}>
                                        Masculino</option>
                                    <option value="F" {{ old('sexo', $paciente->sexo) == 'F' ? 'selected' : '' }}>
                                        Feminino</option>
                                </select>
                                @error('sexo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contato -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-phone me-2"></i>
                            Informações de Contato
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="telefone" class="form-label">Telefone</label>
                                <input type="text" class="form-control @error('telefone') is-invalid @enderror"
                                    id="telefone" name="telefone" value="{{ old('telefone', $paciente->telefone) }}"
                                    placeholder="(00) 00000-0000">
                                @error('telefone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email', $paciente->email) }}"
                                    placeholder="paciente@email.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Endereço -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            Endereço
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="cep" class="form-label">CEP</label>
                                <input type="text" class="form-control @error('cep') is-invalid @enderror"
                                    id="cep" name="cep" value="{{ old('cep', $paciente->cep) }}"
                                    placeholder="00000-000">
                                @error('cep')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-9">
                                <label for="endereco" class="form-label">Endereço</label>
                                <input type="text" class="form-control @error('endereco') is-invalid @enderror"
                                    id="endereco" name="endereco" value="{{ old('endereco', $paciente->endereco) }}"
                                    placeholder="Rua, número">
                                @error('endereco')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="bairro" class="form-label">Bairro</label>
                                <input type="text" class="form-control @error('bairro') is-invalid @enderror"
                                    id="bairro" name="bairro" value="{{ old('bairro', $paciente->bairro) }}">
                                @error('bairro')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="cidade" class="form-label">Cidade</label>
                                <input type="text" class="form-control @error('cidade') is-invalid @enderror"
                                    id="cidade" name="cidade" value="{{ old('cidade', $paciente->cidade) }}">
                                @error('cidade')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-2">
                                <label for="uf" class="form-label">UF</label>
                                <select class="form-select @error('uf') is-invalid @enderror" id="uf"
                                    name="uf">
                                    <option value="">--</option>
                                    @php
                                        $estados = [
                                            'AC',
                                            'AL',
                                            'AP',
                                            'AM',
                                            'BA',
                                            'CE',
                                            'DF',
                                            'ES',
                                            'GO',
                                            'MA',
                                            'MT',
                                            'MS',
                                            'MG',
                                            'PA',
                                            'PB',
                                            'PR',
                                            'PE',
                                            'PI',
                                            'RJ',
                                            'RN',
                                            'RS',
                                            'RO',
                                            'RR',
                                            'SC',
                                            'SP',
                                            'SE',
                                            'TO',
                                        ];
                                    @endphp
                                    @foreach ($estados as $estado)
                                        <option value="{{ $estado }}"
                                            {{ old('uf', $paciente->uf) == $estado ? 'selected' : '' }}>
                                            {{ $estado }}</option>
                                    @endforeach
                                </select>
                                @error('uf')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-toggle-on me-2"></i>
                            Status
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="ativo"
                                name="ativo" value="1" {{ old('ativo', $paciente->ativo) ? 'checked' : '' }}>
                            <label class="form-check-label" for="ativo">
                                Paciente ativo no sistema
                            </label>
                        </div>
                        <small class="text-muted">
                            Pacientes inativos não aparecerão nas buscas principais e não poderão agendar novas consultas.
                        </small>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Resumo de Alterações -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Informações
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Atenção:</strong> Alterações em dados pessoais podem afetar consultas e prontuários
                            existentes.
                        </div>

                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between px-0">
                                <span>Cadastrado em:</span>
                                <span class="small">{{ $paciente->created_at->format('d/m/Y') }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between px-0">
                                <span>Última atualização:</span>
                                <span class="small">{{ $paciente->updated_at->format('d/m/Y') }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between px-0">
                                <span>Total de consultas:</span>
                                <span class="badge bg-primary">{{ $paciente->consultas->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ações -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i>
                                Salvar Alterações
                            </button>
                            <a href="{{ route('pacientes.show', $paciente) }}" class="btn btn-outline-info">
                                <i class="fas fa-eye me-2"></i>
                                Visualizar
                            </a>
                            <a href="{{ route('pacientes.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>
                                Cancelar
                            </a>
                        </div>

                        <hr>

                        <small class="text-muted">
                            <i class="fas fa-shield-alt me-1"></i>
                            Todas as alterações são registradas no sistema para auditoria.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('scripts')
    <script>
        // Máscaras para campos
        $(document).ready(function() {
            $('#cpf').mask('000.000.000-00');
            $('#cartao_sus').mask('000 0000 0000 0000');
            $('#telefone').mask('(00) 00000-0000');
            $('#cep').mask('00000-000');

            // Busca CEP
            $('#cep').blur(function() {
                var cep = $(this).val().replace(/\D/g, '');

                if (cep.length === 8) {
                    $.getJSON('https://viacep.com.br/ws/' + cep + '/json/', function(data) {
                        if (!data.erro) {
                            if (!$('#endereco').val()) $('#endereco').val(data.logradouro);
                            if (!$('#bairro').val()) $('#bairro').val(data.bairro);
                            if (!$('#cidade').val()) $('#cidade').val(data.localidade);
                            if (!$('#uf').val()) $('#uf').val(data.uf);
                        }
                    });
                }
            });
        });
    </script>

    <!-- jQuery Mask Plugin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
@endsection

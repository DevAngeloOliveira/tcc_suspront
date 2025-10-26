@extends('layouts.modern')

@section('title', 'Novo Paciente')

@section('breadcrumb')
    <i class="fas fa-home"></i>
    <span>Dashboard</span>
    <i class="fas fa-chevron-right"></i>
    <a href="{{ route('pacientes.index') }}">Pacientes</a>
    <i class="fas fa-chevron-right"></i>
    <span>Novo Paciente</span>
@endsection

@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">
                    <i class="fas fa-user-plus me-2"></i>
                    Novo Paciente
                </h1>
                <p class="page-subtitle">Cadastre um novo paciente no sistema</p>
            </div>
            <div>
                <a href="{{ route('pacientes.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Voltar
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('pacientes.store') }}" method="POST">
        @csrf

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
                                    id="nome" name="nome" value="{{ old('nome') }}" required autofocus>
                                @error('nome')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="cpf" class="form-label">CPF *</label>
                                <input type="text" class="form-control @error('cpf') is-invalid @enderror" id="cpf"
                                    name="cpf" value="{{ old('cpf') }}" placeholder="000.000.000-00" required>
                                @error('cpf')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="cartao_sus" class="form-label">Cartão SUS *</label>
                                <input type="text" class="form-control @error('cartao_sus') is-invalid @enderror"
                                    id="cartao_sus" name="cartao_sus" value="{{ old('cartao_sus') }}"
                                    placeholder="000 0000 0000 0000" required>
                                @error('cartao_sus')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="data_nascimento" class="form-label">Data de Nascimento *</label>
                                <input type="date" class="form-control @error('data_nascimento') is-invalid @enderror"
                                    id="data_nascimento" name="data_nascimento" value="{{ old('data_nascimento') }}"
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
                                    <option value="M" {{ old('sexo') == 'M' ? 'selected' : '' }}>Masculino</option>
                                    <option value="F" {{ old('sexo') == 'F' ? 'selected' : '' }}>Feminino</option>
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
                                    id="telefone" name="telefone" value="{{ old('telefone') }}"
                                    placeholder="(00) 00000-0000">
                                @error('telefone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email') }}"
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
                                    id="cep" name="cep" value="{{ old('cep') }}" placeholder="00000-000">
                                @error('cep')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-9">
                                <label for="endereco" class="form-label">Endereço</label>
                                <input type="text" class="form-control @error('endereco') is-invalid @enderror"
                                    id="endereco" name="endereco" value="{{ old('endereco') }}"
                                    placeholder="Rua, número">
                                @error('endereco')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="bairro" class="form-label">Bairro</label>
                                <input type="text" class="form-control @error('bairro') is-invalid @enderror"
                                    id="bairro" name="bairro" value="{{ old('bairro') }}">
                                @error('bairro')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="cidade" class="form-label">Cidade</label>
                                <input type="text" class="form-control @error('cidade') is-invalid @enderror"
                                    id="cidade" name="cidade" value="{{ old('cidade') }}">
                                @error('cidade')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-2">
                                <label for="uf" class="form-label">UF</label>
                                <select class="form-select @error('uf') is-invalid @enderror" id="uf"
                                    name="uf">
                                    <option value="">--</option>
                                    <option value="AC" {{ old('uf') == 'AC' ? 'selected' : '' }}>AC</option>
                                    <option value="AL" {{ old('uf') == 'AL' ? 'selected' : '' }}>AL</option>
                                    <option value="AP" {{ old('uf') == 'AP' ? 'selected' : '' }}>AP</option>
                                    <option value="AM" {{ old('uf') == 'AM' ? 'selected' : '' }}>AM</option>
                                    <option value="BA" {{ old('uf') == 'BA' ? 'selected' : '' }}>BA</option>
                                    <option value="CE" {{ old('uf') == 'CE' ? 'selected' : '' }}>CE</option>
                                    <option value="DF" {{ old('uf') == 'DF' ? 'selected' : '' }}>DF</option>
                                    <option value="ES" {{ old('uf') == 'ES' ? 'selected' : '' }}>ES</option>
                                    <option value="GO" {{ old('uf') == 'GO' ? 'selected' : '' }}>GO</option>
                                    <option value="MA" {{ old('uf') == 'MA' ? 'selected' : '' }}>MA</option>
                                    <option value="MT" {{ old('uf') == 'MT' ? 'selected' : '' }}>MT</option>
                                    <option value="MS" {{ old('uf') == 'MS' ? 'selected' : '' }}>MS</option>
                                    <option value="MG" {{ old('uf') == 'MG' ? 'selected' : '' }}>MG</option>
                                    <option value="PA" {{ old('uf') == 'PA' ? 'selected' : '' }}>PA</option>
                                    <option value="PB" {{ old('uf') == 'PB' ? 'selected' : '' }}>PB</option>
                                    <option value="PR" {{ old('uf') == 'PR' ? 'selected' : '' }}>PR</option>
                                    <option value="PE" {{ old('uf') == 'PE' ? 'selected' : '' }}>PE</option>
                                    <option value="PI" {{ old('uf') == 'PI' ? 'selected' : '' }}>PI</option>
                                    <option value="RJ" {{ old('uf') == 'RJ' ? 'selected' : '' }}>RJ</option>
                                    <option value="RN" {{ old('uf') == 'RN' ? 'selected' : '' }}>RN</option>
                                    <option value="RS" {{ old('uf') == 'RS' ? 'selected' : '' }}>RS</option>
                                    <option value="RO" {{ old('uf') == 'RO' ? 'selected' : '' }}>RO</option>
                                    <option value="RR" {{ old('uf') == 'RR' ? 'selected' : '' }}>RR</option>
                                    <option value="SC" {{ old('uf') == 'SC' ? 'selected' : '' }}>SC</option>
                                    <option value="SP" {{ old('uf') == 'SP' ? 'selected' : '' }}>SP</option>
                                    <option value="SE" {{ old('uf') == 'SE' ? 'selected' : '' }}>SE</option>
                                    <option value="TO" {{ old('uf') == 'TO' ? 'selected' : '' }}>TO</option>
                                </select>
                                @error('uf')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Resumo -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Resumo
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-lightbulb me-2"></i>
                            <strong>Dica:</strong> Certifique-se de que todos os dados obrigatórios (*) estejam preenchidos
                            corretamente.
                        </div>

                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between px-0">
                                <span>Campos obrigatórios:</span>
                                <span class="badge bg-primary">6</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between px-0">
                                <span>Campos opcionais:</span>
                                <span class="badge bg-secondary">8</span>
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
                                Salvar Paciente
                            </button>
                            <a href="{{ route('pacientes.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>
                                Cancelar
                            </a>
                        </div>

                        <hr>

                        <small class="text-muted">
                            <i class="fas fa-shield-alt me-1"></i>
                            Todos os dados são criptografados e protegidos conforme a LGPD.
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
                            $('#endereco').val(data.logradouro);
                            $('#bairro').val(data.bairro);
                            $('#cidade').val(data.localidade);
                            $('#uf').val(data.uf);
                        }
                    });
                }
            });
        });
    </script>

    <!-- jQuery Mask Plugin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
@endsection

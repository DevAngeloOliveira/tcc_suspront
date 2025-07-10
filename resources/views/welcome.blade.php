<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SusPront - Sistema de Prontuário Eletrônico</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f8f9fa;
        }

        .hero-section {
            background: linear-gradient(135deg, #198754 0%, #0d6e4c 100%);
            color: white;
            padding: 4rem 0;
        }

        .feature-card {
            transition: transform 0.3s;
            cursor: pointer;
        }

        .feature-card:hover {
            transform: translateY(-5px);
        }

        .feature-icon {
            font-size: 2.5rem;
            color: #198754;
            margin-bottom: 1rem;
        }

        .navbar {
            background-color: rgba(255, 255, 255, 0.95);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .btn-outline-light:hover {
            background-color: #ffffff;
            color: #198754;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-heartbeat text-success"></i> SusPront
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @if (Route::has('login'))
                        @auth
                            <li class="nav-item">
                                <a href="{{ url('/dashboard') }}" class="nav-link">Dashboard</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a href="{{ route('login') }}" class="nav-link">Entrar</a>
                            </li>
                        @endauth
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">Bem-vindo ao SusPront</h1>
                    <p class="lead mb-4">Sistema integrado de prontuário eletrônico para melhor atendimento na rede
                        pública de saúde.</p>
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn btn-outline-light btn-lg">Acessar Sistema</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">Entrar no Sistema</a>
                        @endauth
                    @endif
                </div>
                <div class="col-lg-6">
                    <img src="https://placehold.co/600x400" alt="Ilustração do Sistema"
                        class="img-fluid rounded shadow">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Serviços Disponíveis</h2>
            <div class="row g-4">
                <!-- Para Pacientes -->
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm feature-card">
                        <div class="card-body text-center">
                            <i class="fas fa-user-injured feature-icon"></i>
                            <h3 class="h4 mb-3">Para Pacientes</h3>
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Agendamento de
                                    consultas online</li>
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Visualização de
                                    resultados de exames</li>
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Histórico de
                                    consultas</li>
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Acesso ao
                                    prontuário digital</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Para Médicos -->
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm feature-card">
                        <div class="card-body text-center">
                            <i class="fas fa-user-md feature-icon"></i>
                            <h3 class="h4 mb-3">Para Profissionais de Saúde</h3>
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Gestão de
                                    prontuários</li>
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Agenda de
                                    atendimentos</li>
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Solicitação de
                                    exames</li>
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Histórico médico
                                    completo</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-light py-4">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <p class="mb-0">© 2024 SusPront - Sistema de Prontuário Eletrônico</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

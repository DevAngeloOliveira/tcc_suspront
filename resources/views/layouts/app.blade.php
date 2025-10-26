<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - SusPront</title>

    <!-- CSS Bootstrap e Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Livewire Styles -->
    @livewireStyles

    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f8f9fa;
        }

        .sidebar {
            min-height: 100vh;
            background-color: #198754;
            color: #fff;
            padding: 0;
        }

        .sidebar-brand {
            padding: 20px 15px;
            font-size: 22px;
            font-weight: bold;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            margin-bottom: 20px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar li a {
            display: block;
            color: rgba(255, 255, 255, 0.85);
            padding: 12px 20px;
            text-decoration: none;
            transition: all 0.3s;
        }

        .sidebar li a:hover,
        .sidebar li a.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .sidebar li a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .content {
            padding: 20px;
        }

        .user-info {
            margin-top: auto;
            padding: 15px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            font-size: 14px;
        }

        .top-bar {
            background-color: #fff;
            border-bottom: 1px solid #e9ecef;
            padding: 15px 20px;
        }

        .page-title {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .card {
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        .card-header {
            background-color: #fff;
            border-bottom: 1px solid rgba(0, 0, 0, .125);
            font-weight: 600;
        }

        .btn-primary {
            background-color: #198754;
            border-color: #198754;
        }

        .btn-primary:hover {
            background-color: #157347;
            border-color: #157347;
        }
    </style>

    @yield('styles')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="d-flex flex-column h-100">
                    <div class="sidebar-brand">
                        <a href="{{ route('dashboard') }}" class="text-white text-decoration-none">
                            <i class="fas fa-heartbeat"></i> SusPront
                        </a>
                    </div>

                    <ul class="nav flex-column mb-auto">
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}"
                                class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('pacientes.index') }}"
                                class="nav-link {{ request()->routeIs('pacientes.*') ? 'active' : '' }}">
                                <i class="fas fa-user-injured"></i> Pacientes
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('prontuarios.index') }}"
                                class="nav-link {{ request()->routeIs('prontuarios.*') ? 'active' : '' }}">
                                <i class="fas fa-file-medical-alt"></i> Prontuários
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('consultas.index') }}"
                                class="nav-link {{ request()->routeIs('consultas.*') ? 'active' : '' }}">
                                <i class="fas fa-stethoscope"></i> Consultas
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('exames.index') }}"
                                class="nav-link {{ request()->routeIs('exames.*') ? 'active' : '' }}">
                                <i class="fas fa-microscope"></i> Exames
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('receitas.index') }}"
                                class="nav-link {{ request()->routeIs('receitas.*') ? 'active' : '' }}">
                                <i class="fas fa-prescription"></i> Receitas
                            </a>
                        </li>

                        @if (auth()->check() && (auth()->user()->tipo == 'admin' || auth()->user()->tipo == 'atendente'))
                            <li class="nav-item">
                                <a href="{{ route('medicos.index') }}"
                                    class="nav-link {{ request()->routeIs('medicos.*') ? 'active' : '' }}">
                                    <i class="fas fa-user-md"></i> Médicos
                                </a>
                            </li>
                        @endif

                        @if (auth()->check() && auth()->user()->tipo == 'admin')
                            <li class="nav-item">
                                <a href="{{ route('atendentes.index') }}"
                                    class="nav-link {{ request()->routeIs('atendentes.*') ? 'active' : '' }}">
                                    <i class="fas fa-user-nurse"></i> Atendentes
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('api.documentation') }}"
                                    class="nav-link {{ request()->routeIs('api.documentation') ? 'active' : '' }}">
                                    <i class="fas fa-code"></i> Documentação da API
                                </a>
                            </li>
                        @endif
                    </ul>

                    <div class="user-info">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-user-circle me-2"></i>
                            <span>{{ auth()->check() ? auth()->user()->name : 'Usuário' }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <small class="text-capitalize">{{ auth()->check() ? auth()->user()->tipo : '' }}</small>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-link p-0 text-white">
                                    <i class="fas fa-sign-out-alt"></i> Sair
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="top-bar d-flex justify-content-between align-items-center mb-4">
                    <h1 class="page-title">@yield('title')</h1>
                    <div class="d-flex align-items-center">
                        <!-- Componente de notificações -->
                        <livewire:notificacoes.notification-badge />

                        <button class="navbar-toggler d-md-none collapsed ms-2" type="button" data-bs-toggle="collapse"
                            data-bs-target=".sidebar" aria-controls="sidebar" aria-expanded="false"
                            aria-label="Toggle navigation">
                            <i class="fas fa-bars"></i>
                        </button>
                    </div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="content">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Scripts Bootstrap e jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> <!-- Alpine.js e Livewire Scripts -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @livewireScripts

    @yield('scripts')
</body>

</html>

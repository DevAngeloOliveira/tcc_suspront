<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - SusPront</title>

    <!-- CSS Bootstrap e Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Livewire Styles -->
    @livewireStyles

    <!-- SusPront Modular Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Page-specific styles -->
    @stack('styles')
    @yield('styles')
</head>

<body>
    <div class="main-wrapper">
        <!-- Mobile Sidebar Overlay -->
        <div class="sidebar-overlay" id="sidebar-overlay"></div>

        <!-- Sidebar -->
        <nav class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <a href="{{ route('dashboard') }}" class="sidebar-brand">
                    <div class="brand-icon">
                        <i class="fas fa-heartbeat"></i>
                    </div>
                    <div class="brand-text">
                        <h4>SusPront</h4>
                        <small>Sistema SUS</small>
                    </div>
                </a>
            </div>

            <nav class="sidebar-nav">
                <ul class="nav-list">
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}"
                            class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <span class="nav-text">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('pacientes.index') }}"
                            class="nav-link {{ request()->routeIs('pacientes.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <span class="nav-text">Pacientes</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('consultas.index') }}"
                            class="nav-link {{ request()->routeIs('consultas.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-calendar-check"></i>
                            <span class="nav-text">Consultas</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('prontuarios.index') }}"
                            class="nav-link {{ request()->routeIs('prontuarios.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-medical"></i>
                            <span class="nav-text">Prontuários</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('exames.index') }}"
                            class="nav-link {{ request()->routeIs('exames.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-vial"></i>
                            <span class="nav-text">Exames</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('receitas.index') }}"
                            class="nav-link {{ request()->routeIs('receitas.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-prescription-bottle-alt"></i>
                            <span class="nav-text">Receitas</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('medicos.index') }}"
                            class="nav-link {{ request()->routeIs('medicos.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-md"></i>
                            <span class="nav-text">Médicos</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('atendentes.index') }}"
                            class="nav-link {{ request()->routeIs('atendentes.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-id-badge"></i>
                            <span class="nav-text">Atendentes</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('notificacoes.index') }}"
                            class="nav-link {{ request()->routeIs('notificacoes.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-bell"></i>
                            <span class="nav-text">Notificações</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </nav>

        <!-- Main Content -->
        <main class="main-content" id="main-content">
            <!-- Top Navbar -->
            <header class="top-navbar">
                <div class="navbar-left">
                    <button class="nav-toggle" id="nav-toggle">
                        <i class="fas fa-bars"></i>
                    </button>

                    @hasSection('breadcrumb')
                        <nav class="breadcrumb-nav">
                            @yield('breadcrumb')
                        </nav>
                    @endif
                </div>

                <div class="navbar-right">
                    <div class="nav-item">
                        <a href="{{ route('notificacoes.index') }}" class="nav-link">
                            <i class="fas fa-bell"></i>
                            @if (auth()->user()->unreadNotifications->count() > 0)
                                <span class="badge bg-danger">{{ auth()->user()->unreadNotifications->count() }}</span>
                            @endif
                        </a>
                    </div>

                    <div class="dropdown user-dropdown">
                        <a class="user-avatar" href="#" role="button" data-bs-toggle="dropdown">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user me-2"></i>
                                    {{ auth()->user()->name }}
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cog me-2"></i>
                                    Configurações
                                </a>
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt me-2"></i>
                                        Sair
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <section class="content-area">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        {{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('info'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        {{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </section>
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery (para funcionalidades legadas) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Livewire Scripts -->
    @livewireScripts

    <!-- SusPront JavaScript -->
    <script>
        // Sidebar toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const navToggle = document.getElementById('nav-toggle');
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const sidebarOverlay = document.getElementById('sidebar-overlay');
            const isMobile = () => window.innerWidth <= 768;

            function toggleSidebar() {
                if (isMobile()) {
                    // Mobile behavior
                    sidebar.classList.toggle('show');
                    sidebarOverlay.classList.toggle('show');
                    document.body.style.overflow = sidebar.classList.contains('show') ? 'hidden' : '';
                } else {
                    // Desktop behavior
                    sidebar.classList.toggle('collapsed');
                    mainContent.classList.toggle('expanded');
                }
            }

            function closeSidebar() {
                if (isMobile()) {
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                    document.body.style.overflow = '';
                }
            }

            // Toggle sidebar
            navToggle.addEventListener('click', toggleSidebar);

            // Close sidebar when clicking overlay (mobile)
            sidebarOverlay.addEventListener('click', closeSidebar);

            // Close sidebar on window resize if mobile
            window.addEventListener('resize', function() {
                if (!isMobile()) {
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                    document.body.style.overflow = '';
                }
            });

            // Close sidebar when clicking nav links on mobile
            document.querySelectorAll('.sidebar .nav-link').forEach(link => {
                link.addEventListener('click', () => {
                    if (isMobile()) {
                        closeSidebar();
                    }
                });
            });

            // Prevent body scroll when sidebar is open on mobile
            sidebar.addEventListener('touchmove', function(e) {
                if (isMobile() && sidebar.classList.contains('show')) {
                    e.stopPropagation();
                }
            });
        });

        // Auto-hide alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Smooth scroll for internal links
        document.addEventListener('DOMContentLoaded', function() {
            const links = document.querySelectorAll('a[href^="#"]');
            links.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        });

        // Enhanced dropdown functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-close dropdowns when clicking outside
            document.addEventListener('click', function(e) {
                const dropdowns = document.querySelectorAll('.dropdown-menu.show');
                dropdowns.forEach(dropdown => {
                    if (!dropdown.closest('.dropdown').contains(e.target)) {
                        bootstrap.Dropdown.getInstance(dropdown.previousElementSibling)?.hide();
                    }
                });
            });
        });
    </script>

    <!-- Page-specific scripts -->
    @stack('scripts')
    @yield('scripts')
</body>

</html>

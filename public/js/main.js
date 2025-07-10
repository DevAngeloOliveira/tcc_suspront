/**
 * SusPront - Sistema de Prontuário Eletrônico para o SUS
 * Script principal
 */

document.addEventListener('DOMContentLoaded', function() {
    // Configurar AJAX para CSRF
    setupAjaxCsrf();

    // Inicializar componentes do Bootstrap
    inicializarComponentesBootstrap();

    // Configurar menu lateral
    setupSidebar();

    // Configurar alertas temporários
    setupTemporaryAlerts();

    // Configurar temas (modo claro/escuro)
    setupThemeToggle();

    // Inicializar gráficos na dashboard
    if (document.getElementById('dashboard-charts')) {
        inicializarGraficosDashboard();
    }

    // Anexar manipuladores de eventos para links de ações
    setupActionLinks();
});

/**
 * Configura o token CSRF para requisições AJAX
 */
function setupAjaxCsrf() {
    // Adicionar token CSRF em todas as requisições AJAX
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Configurar cabeçalho para todas as requisições fetch
    window.fetchWithCsrf = function(url, options = {}) {
        options.headers = options.headers || {};
        options.headers['X-CSRF-TOKEN'] = token;

        return fetch(url, options);
    };

    // Interceptar requisições XMLHttpRequest
    const originalXhrOpen = window.XMLHttpRequest.prototype.open;
    window.XMLHttpRequest.prototype.open = function() {
        const result = originalXhrOpen.apply(this, arguments);
        this.setRequestHeader('X-CSRF-TOKEN', token);
        return result;
    };
}

/**
 * Inicializa componentes do Bootstrap
 */
function inicializarComponentesBootstrap() {
    // Tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    if (tooltipTriggerList.length > 0) {
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }

    // Popovers
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    if (popoverTriggerList.length > 0) {
        popoverTriggerList.map(function(popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });
    }

    // Toasts
    var toastElList = [].slice.call(document.querySelectorAll('.toast'));
    if (toastElList.length > 0) {
        toastElList.map(function(toastEl) {
            return new bootstrap.Toast(toastEl).show();
        });
    }
}

/**
 * Configura o menu lateral
 */
function setupSidebar() {
    const toggleSidebar = document.getElementById('sidebarToggle');
    if (toggleSidebar) {
        toggleSidebar.addEventListener('click', function() {
            document.body.classList.toggle('sidebar-collapsed');

            // Salvar estado no localStorage
            const isCollapsed = document.body.classList.contains('sidebar-collapsed');
            localStorage.setItem('sidebar-collapsed', isCollapsed);
        });

        // Verificar estado salvo
        if (localStorage.getItem('sidebar-collapsed') === 'true') {
            document.body.classList.add('sidebar-collapsed');
        }
    }

    // Destacar item de menu ativo
    const currentPath = window.location.pathname;
    const menuItems = document.querySelectorAll('.sidebar-menu a');

    menuItems.forEach(function(item) {
        const href = item.getAttribute('href');
        if (href === currentPath || (href !== '/' && currentPath.startsWith(href))) {
            item.classList.add('active');

            // Expandir menu pai se estiver dentro de um submenu
            const parentLi = item.closest('li.has-submenu');
            if (parentLi) {
                parentLi.classList.add('menu-open');
            }
        }
    });
}

/**
 * Configura alertas temporários
 */
function setupTemporaryAlerts() {
    const alerts = document.querySelectorAll('.alert-dismissible:not(.alert-permanent)');

    alerts.forEach(function(alert) {
        setTimeout(function() {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
}

/**
 * Configura alternância de tema (claro/escuro)
 */
function setupThemeToggle() {
    const themeToggle = document.getElementById('theme-toggle');
    if (themeToggle) {
        // Verificar tema atual
        const currentTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-bs-theme', currentTheme);

        if (currentTheme === 'dark') {
            themeToggle.innerHTML = '<i class="fas fa-sun"></i>';
        } else {
            themeToggle.innerHTML = '<i class="fas fa-moon"></i>';
        }

        // Alternar tema ao clicar
        themeToggle.addEventListener('click', function() {
            const currentTheme = document.documentElement.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

            document.documentElement.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('theme', newTheme);

            // Atualizar ícone
            if (newTheme === 'dark') {
                themeToggle.innerHTML = '<i class="fas fa-sun"></i>';
            } else {
                themeToggle.innerHTML = '<i class="fas fa-moon"></i>';
            }
        });
    }
}

/**
 * Inicializa gráficos na dashboard
 */
function inicializarGraficosDashboard() {
    // Verificar se Chart.js está disponível
    if (typeof Chart === 'undefined') {
        console.warn('Chart.js não está disponível. Inclua a biblioteca para usar os gráficos.');
        return;
    }

    // Gráfico de consultas por mês
    const ctxConsultas = document.getElementById('graficoConsultas');
    if (ctxConsultas) {
        const data = JSON.parse(ctxConsultas.getAttribute('data-consultas'));
        const labels = [];
        const values = [];

        // Meses em português
        const meses = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
                      'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];

        // Extrair dados
        data.forEach(item => {
            labels.push(meses[item.mes - 1]);
            values.push(item.total);
        });

        new Chart(ctxConsultas, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Consultas',
                    data: values,
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Consultas por Mês'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    }

    // Gráfico de distribuição de pacientes por sexo
    const ctxPacientes = document.getElementById('graficoPacientes');
    if (ctxPacientes) {
        const data = JSON.parse(ctxPacientes.getAttribute('data-pacientes'));

        new Chart(ctxPacientes, {
            type: 'pie',
            data: {
                labels: ['Masculino', 'Feminino', 'Outro'],
                datasets: [{
                    data: [data.masculino, data.feminino, data.outro],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(255, 206, 86, 0.6)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 206, 86, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Distribuição de Pacientes por Sexo'
                    }
                }
            }
        });
    }
}

/**
 * Configura links de ação (confirmação de exclusão, etc)
 */
function setupActionLinks() {
    // Links de exclusão com confirmação
    const deleteLinks = document.querySelectorAll('a[data-confirm], button[data-confirm]');

    deleteLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            const message = this.getAttribute('data-confirm') || 'Tem certeza que deseja excluir este item?';
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });

    // Links para marcar consulta como concluída
    const concludeLinks = document.querySelectorAll('.btn-concluir-consulta');

    concludeLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();

            const consultaId = this.getAttribute('data-id');
            const form = document.querySelector(`form[data-consulta-id="${consultaId}"]`);

            if (form) {
                if (confirm('Deseja realmente marcar esta consulta como concluída?')) {
                    form.submit();
                }
            }
        });
    });
}

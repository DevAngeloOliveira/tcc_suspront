/**
 * SusPront - Sistema de Prontuário Eletrônico para o SUS
 * Script de inicialização
 */

// Importar bibliotecas
// document.write('<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>');
// document.write('<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>');

// Importar scripts do sistema
document.write('<script src="/js/main.js"></script>');
document.write('<script src="/js/forms.js"></script>');

// Carregar scripts específicos de módulo conforme a página atual
const currentPath = window.location.pathname;

if (currentPath.includes('/consultas')) {
    document.write('<script src="/js/consultas.js"></script>');
}

if (currentPath.includes('/exames')) {
    document.write('<script src="/js/exames.js"></script>');
}

if (currentPath.includes('/prontuarios')) {
    document.write('<script src="/js/prontuarios.js"></script>');
}

// Inicializar scripts específicos por rota
document.addEventListener('DOMContentLoaded', function() {
    console.log('SusPront - Sistema de Prontuário Eletrônico inicializado');

    // Verificar ambiente de desenvolvimento
    if (document.querySelector('meta[name="environment"]')?.getAttribute('content') === 'development') {
        console.log('Ambiente de desenvolvimento ativo');
    }
});

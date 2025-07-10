# Testes do Sistema de Consultas, Receitas e Notificações
# Este script executa os testes específicos para as funcionalidades implementadas

Write-Host "======================================================="
Write-Host "  Executando testes do sistema SusPront"
Write-Host "======================================================="
Write-Host

Write-Host "Verificando ambiente de teste..."
if (Test-Path "c:\Users\Lancelloth\Workspace\tcc_suspront\vendor\bin\phpunit") {
    $phpunit = "c:\Users\Lancelloth\Workspace\tcc_suspront\vendor\bin\phpunit"
    Write-Host "PHPUnit encontrado!" -ForegroundColor Green
} else {
    Write-Host "PHPUnit não encontrado, tentando usar PHPUnit global..." -ForegroundColor Yellow
    $phpunit = "phpunit"
}

# Mover para o diretório do projeto
Set-Location c:\Users\Lancelloth\Workspace\tcc_suspront

Write-Host "`nExecutando testes de Receitas..."
Write-Host "-------------------------------------------------------"
& $phpunit tests/Feature/ReceitaControllerTest.php --colors=always

Write-Host "`nExecutando testes de Remarcação de Consultas..."
Write-Host "-------------------------------------------------------"
& $phpunit tests/Feature/ConsultaRemarcacaoControllerTest.php --colors=always

Write-Host "`nExecutando testes de Notificações..."
Write-Host "-------------------------------------------------------"
& $phpunit tests/Feature/NotificacaoControllerTest.php --colors=always

Write-Host "`nExecutando testes do componente Livewire de Notificações..."
Write-Host "-------------------------------------------------------"
& $phpunit tests/Feature/Livewire/NotificationBadgeTest.php --colors=always

Write-Host "`n======================================================="
Write-Host "  Todos os testes foram executados!"
Write-Host "======================================================="

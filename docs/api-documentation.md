# Documentação das APIs do SusPront

Este documento descreve as APIs disponíveis no sistema SusPront (Sistema de Prontuário Eletrônico para o SUS).

## Autenticação

Todas as APIs requerem autenticação e incluem o token CSRF em cada requisição. O token é obtido a partir da meta tag `csrf-token` em todas as páginas do sistema.

## Consultas

### Listar todas as consultas
- **URL**: `/api/consultas`
- **Método**: GET
- **Descrição**: Retorna todas as consultas no formato compatível com o calendário FullCalendar.
- **Parâmetros de Query**:
  - `start` (opcional): Data de início para filtrar consultas (formato YYYY-MM-DD)
  - `end` (opcional): Data de fim para filtrar consultas (formato YYYY-MM-DD)
- **Retorno**: Array de objetos com estrutura compatível com FullCalendar

### Obter detalhes de uma consulta
- **URL**: `/api/consultas/{id}`
- **Método**: GET
- **Descrição**: Retorna detalhes de uma consulta específica
- **Parâmetros**:
  - `id`: ID da consulta
- **Retorno**: Objeto com os detalhes da consulta, incluindo paciente e médico relacionados

### Verificar horários disponíveis
- **URL**: `/api/consultas/horarios-disponiveis`
- **Método**: GET
- **Descrição**: Retorna os horários disponíveis para agendamento com um médico em uma data específica
- **Parâmetros de Query**:
  - `data`: Data para verificar disponibilidade (formato YYYY-MM-DD)
  - `medico_id`: ID do médico
- **Retorno**: Objeto com array de horários disponíveis (`{ "horarios": ["08:00", "08:30", ...] }`)

### Atualizar status de uma consulta
- **URL**: `/api/consultas/{id}/status`
- **Método**: PUT
- **Descrição**: Atualiza o status de uma consulta específica
- **Parâmetros**:
  - `id`: ID da consulta
  - `status`: Novo status da consulta ('agendada', 'confirmada', 'em_andamento', 'concluida', 'cancelada')
- **Retorno**: Objeto com mensagem de sucesso e dados atualizados da consulta

## Médicos

### Obter médicos por especialidade
- **URL**: `/api/medicos/especialidade/{especialidade}`
- **Método**: GET
- **Descrição**: Retorna lista de médicos de uma especialidade específica
- **Parâmetros**:
  - `especialidade`: Nome da especialidade médica
- **Retorno**: Array de objetos médicos filtrados pela especialidade

## Pacientes

### Obter consultas de um paciente
- **URL**: `/api/pacientes/{id}/consultas`
- **Método**: GET
- **Descrição**: Retorna o histórico de consultas de um paciente específico
- **Parâmetros**:
  - `id`: ID do paciente
- **Retorno**: Array de objetos de consultas do paciente

## Tratamento de Erros

Todas as APIs retornam códigos de status HTTP apropriados:
- 200: Sucesso
- 400: Erro de validação ou formato dos dados
- 403: Acesso negado (sem permissão)
- 404: Recurso não encontrado
- 500: Erro interno do servidor

Para erros, as respostas incluem um objeto JSON com detalhes do erro:
```json
{
  "error": "Mensagem descritiva do erro"
}
```

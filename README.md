<p align="center"><img src="public/img/logo.png" alt="SusPront Logo" width="200"></p>

<h1 align="center">SusPront</h1>

<p align="center">
Sistema de Prontuário Eletrônico para o SUS
</p>

<p align="center">
<a href="#sobre">Sobre</a> •
<a href="#funcionalidades">Funcionalidades</a> •
<a href="#tecnologias">Tecnologias</a> •
<a href="#instalação">Instalação</a> •
<a href="#uso">Uso</a> •
<a href="#estrutura">Estrutura</a> •
<a href="#equipe">Equipe</a> •
<a href="#licença">Licença</a>
</p>

## Sobre

O **SusPront** é um sistema de prontuário eletrônico desenvolvido para atender às necessidades das unidades de saúde vinculadas ao Sistema Único de Saúde (SUS) no Brasil. Este sistema foi projetado para digitalizar e centralizar os registros médicos, facilitando o gerenciamento de pacientes, consultas, exames e histórico médico em unidades de saúde pública.

O projeto visa melhorar a eficiência operacional dos serviços de saúde, permitir melhor acompanhamento de pacientes e fornecer dados confiáveis para análises e tomadas de decisão no contexto da saúde pública brasileira.

## Funcionalidades

### Gestão de Pacientes
- Cadastro completo de pacientes com dados pessoais e histórico médico
- Busca avançada de pacientes por diversos critérios
- Visualização de histórico médico completo
- Gestão de prontuários individuais

### Gestão de Profissionais
- Cadastro de médicos com especialidades e credenciais (CRM)
- Cadastro de atendentes e pessoal administrativo
- Controle de acesso baseado em perfil (médico, atendente, administrador)

### Agendamento e Consultas
- Agendamento de novas consultas
- Confirmação e cancelamento de consultas
- Registro de atendimentos médicos
- Histórico de consultas por paciente e médico

### Prontuário Eletrônico
- Registro de histórico médico
- Controle de medicamentos atuais
- Anotações e observações clínicas
- Vinculação com consultas e exames

### Exames
- Solicitação de exames
- Acompanhamento de status (solicitado, agendado, realizado, cancelado)
- Upload e visualização de resultados
- Histórico de exames por paciente

### Dashboard e Relatórios
- Visão geral de estatísticas do sistema
- Consultas agendadas para o dia
- Indicadores de performance
- Monitoramento de atividades

## Tecnologias

O sistema foi desenvolvido utilizando as seguintes tecnologias e frameworks:

- **[Laravel](https://laravel.com/)** - Framework PHP para desenvolvimento web
- **[MySQL](https://www.mysql.com/)** - Sistema de gerenciamento de banco de dados relacional
- **[Bootstrap 5](https://getbootstrap.com/)** - Framework front-end para design responsivo
- **[Font Awesome](https://fontawesome.com/)** - Biblioteca de ícones
- **[jQuery](https://jquery.com/)** - Biblioteca JavaScript para manipulação do DOM
- **[Chart.js](https://www.chartjs.org/)** - Biblioteca para criação de gráficos

## Instalação

### Pré-requisitos
- PHP 8.1 ou superior
- Composer
- MySQL ou MariaDB
- Node.js e NPM

### Passos para Instalação

1. Clone o repositório
```bash
git clone https://github.com/seu-usuario/suspront.git
cd suspront
```

2. Instale as dependências PHP
```bash
composer install
```

3. Instale as dependências JavaScript
```bash
npm install
```

4. Crie o arquivo de ambiente
```bash
cp .env.example .env
```

5. Gere a chave da aplicação
```bash
php artisan key:generate
```

6. Configure o banco de dados no arquivo `.env`
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=suspront
DB_USERNAME=root
DB_PASSWORD=
```

7. Execute as migrações
```bash
php artisan migrate
```

8. (Opcional) Execute os seeders para dados de teste
```bash
php artisan db:seed
```

9. Configure o storage para uploads
```bash
php artisan storage:link
```

10. Compile os assets
```bash
npm run dev
```

11. Inicie o servidor de desenvolvimento
```bash
php artisan serve
```

O sistema estará disponível em `http://localhost:8000`

## Uso

### Credenciais Padrão

Para acesso inicial ao sistema (quando usando os seeders):

- **Administrador**:
  - Email: admin@suspront.gov.br
  - Senha: password

- **Médico**:
  - Email: medico@suspront.gov.br
  - Senha: password

- **Atendente**:
  - Email: atendente@suspront.gov.br
  - Senha: password

### Permissões por Perfil

- **Administrador**: Acesso total ao sistema
- **Médico**: Gerenciamento de pacientes, consultas, prontuários e exames
- **Atendente**: Cadastro de pacientes, agendamento de consultas e exames

## Estrutura

### Módulos do Sistema

O sistema está organizado nos seguintes módulos principais:

1. **Pacientes**: Gerenciamento de cadastros de pacientes
2. **Médicos**: Gerenciamento do corpo clínico
3. **Atendentes**: Gestão do pessoal administrativo
4. **Consultas**: Agendamento e registro de atendimentos
5. **Prontuários**: Histórico médico dos pacientes
6. **Exames**: Solicitações e resultados de exames

### Estrutura de Diretórios

- **app/Models/**: Modelos do sistema (Paciente, Medico, Prontuario, etc.)
- **app/Http/Controllers/**: Controladores para cada módulo
- **resources/views/**: Views organizadas por módulo
- **database/migrations/**: Migrações do banco de dados
- **public/**: Arquivos públicos (CSS, JavaScript, imagens)
- **storage/app/public/**: Arquivos de upload (resultados de exames, etc.)

## Equipe

Este projeto foi desenvolvido como Trabalho de Conclusão de Curso (TCC) por:

- **Gabriel Ângelo Oliveira Silva** - *Desenvolvedor* - [GitHub](https://github.com/DevAngeloOliveira)
- **Thiago Rodrigues** - *Professor* - Centro Universitário de João Pessoa

## Licença

Este projeto está licenciado sob a [Licença MIT](LICENSE) - veja o arquivo LICENSE para mais detalhes.

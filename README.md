<p align="center"><img src="public/img/logo.png" alt="SusPront Logo" width="200"></p>

<h1 align="center">SusPront</h1>

<p align="center">
Sistema de Prontuário Eletrônico para o SUS
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.0-FF2D20?style=flat&logo=laravel&logoColor=white" alt="Laravel 12.0">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat&logo=php&logoColor=white" alt="PHP 8.2+">
  <img src="https://img.shields.io/badge/Livewire-3.6-4E56A6?style=flat&logo=livewire&logoColor=white" alt="Livewire 3.6">
  <img src="https://img.shields.io/badge/TailwindCSS-4.0-38B2AC?style=flat&logo=tailwind-css&logoColor=white" alt="Tailwind CSS 4.0">
  <img src="https://img.shields.io/badge/Alpine.js-3.14-8BC0D0?style=flat&logo=alpine.js&logoColor=white" alt="Alpine.js">
  <img src="https://img.shields.io/badge/License-MIT-green.svg" alt="License MIT">
</p>

<p align="center">
<a href="#sobre">Sobre</a> •
<a href="#funcionalidades">Funcionalidades</a> •
<a href="#tecnologias">Tecnologias</a> •
<a href="#instalação">Instalação</a> •
<a href="#uso">Uso</a> •
<a href="#estrutura">Estrutura</a> •
<a href="#testes">Testes</a> •
<a href="#documentação">Documentação</a> •
<a href="#equipe">Equipe</a> •
<a href="#licença">Licença</a>
</p>

## Sobre

O **SusPront** é um sistema de prontuário eletrônico desenvolvido para atender às necessidades das unidades de saúde vinculadas ao Sistema Único de Saúde (SUS) no Brasil. Este sistema foi projetado para digitalizar e centralizar os registros médicos, facilitando o gerenciamento de pacientes, consultas, exames e histórico médico em unidades de saúde pública.

O projeto visa melhorar a eficiência operacional dos serviços de saúde, permitir melhor acompanhamento de pacientes e fornecer dados confiáveis para análises e tomadas de decisão no contexto da saúde pública brasileira.

## Funcionalidades

### 👥 Gestão de Pacientes
- ✅ Cadastro completo de pacientes com dados pessoais e histórico médico
- ✅ Busca avançada e filtragem em tempo real com Livewire
- ✅ Visualização de histórico médico completo
- ✅ Gestão de prontuários individuais
- ✅ Interface reativa e responsiva

### 👨‍⚕️ Gestão de Profissionais
- ✅ Cadastro de médicos com especialidades e credenciais (CRM)
- ✅ Gestão de plantões médicos
- ✅ Cadastro de atendentes e pessoal administrativo
- ✅ Controle de acesso baseado em perfil (médico, atendente, administrador)
- ✅ Busca e filtros avançados com Livewire

### 📅 Agendamento e Consultas
- ✅ Agendamento de novas consultas
- ✅ Sistema de remarcação de consultas
- ✅ Confirmação e cancelamento de consultas com motivo
- ✅ Registro de atendimentos médicos
- ✅ Evolução do paciente durante consulta
- ✅ Histórico de consultas por paciente e médico
- ✅ Visualização em calendário interativo

### 📋 Prontuário Eletrônico
- ✅ Registro de histórico médico detalhado
- ✅ Controle de medicamentos atuais
- ✅ Anotações e observações clínicas
- ✅ Evolução do quadro clínico
- ✅ Vinculação com consultas e exames
- ✅ Interface moderna com Livewire

### 🔬 Exames
- ✅ Solicitação de exames laboratoriais
- ✅ Agendamento de exames
- ✅ Acompanhamento de status (solicitado, agendado, realizado, cancelado)
- ✅ Upload e visualização de resultados
- ✅ Histórico completo de exames por paciente
- ✅ Integração com prontuário

### 💊 Receitas Médicas
- ✅ Emissão de receitas médicas digitais
- ✅ Impressão de receitas em PDF
- ✅ Histórico de receitas por paciente
- ✅ Validação e controle de prescrições

### 🔔 Sistema de Notificações
- ✅ Notificações em tempo real
- ✅ Alertas de consultas e exames
- ✅ Marcação de notificações como lidas
- ✅ Badge de notificações não lidas
- ✅ Central de notificações

### 📊 Dashboard e Relatórios
- ✅ Dashboard interativo com componentes Livewire
- ✅ Estatísticas do sistema em tempo real
- ✅ Gráficos de consultas e atendimentos
- ✅ Consultas agendadas para o dia
- ✅ Indicadores de performance
- ✅ Links rápidos para ações principais
- ✅ Notificações recentes

## Tecnologias

O sistema foi desenvolvido utilizando tecnologias modernas e frameworks atualizados:

### Backend
- **[Laravel 12.0](https://laravel.com/)** - Framework PHP moderno para desenvolvimento web
- **[PHP 8.2+](https://www.php.net/)** - Linguagem de programação
- **[MySQL](https://www.mysql.com/)** ou **SQLite** - Sistemas de gerenciamento de banco de dados
- **[Livewire 3.6](https://livewire.laravel.com/)** - Framework full-stack para Laravel (componentes reativos)

### Frontend
- **[Tailwind CSS 4.0](https://tailwindcss.com/)** - Framework CSS utility-first
- **[Alpine.js 3.14](https://alpinejs.dev/)** - Framework JavaScript leve e reativo
- **[Vite 6.2](https://vitejs.dev/)** - Build tool e desenvolvimento
- **[Axios](https://axios-http.com/)** - Cliente HTTP para requisições

### Ferramentas e Bibliotecas
- **[DomPDF](https://github.com/barryvdh/laravel-dompdf)** - Geração de PDFs (receitas médicas)
- **[Font Awesome](https://fontawesome.com/)** - Biblioteca de ícones
- **[Chart.js](https://www.chartjs.org/)** - Biblioteca para criação de gráficos

### Desenvolvimento
- **[Laravel Pint](https://laravel.com/docs/pint)** - Formatador de código PHP
- **[PHPUnit](https://phpunit.de/)** - Framework de testes unitários
- **[Laravel Sail](https://laravel.com/docs/sail)** - Ambiente de desenvolvimento Docker
- **[Concurrently](https://www.npmjs.com/package/concurrently)** - Execução paralela de comandos

## Instalação

### Pré-requisitos
- **PHP 8.2** ou superior
- **Composer** (gerenciador de dependências PHP)
- **MySQL 8.0+** ou **MariaDB** (ou SQLite para desenvolvimento)
- **Node.js 18+** e **NPM** (ou Yarn)

### Passos para Instalação

#### 1. Clone o repositório
```bash
git clone https://github.com/DevAngeloOliveira/tcc_suspront.git
cd tcc_suspront
```

#### 2. Instale as dependências PHP
```bash
composer install
```

#### 3. Instale as dependências JavaScript
```bash
npm install
```

#### 4. Configure o ambiente
Crie o arquivo `.env` a partir do exemplo:
```bash
cp .env.example .env
```

Gere a chave da aplicação:
```bash
php artisan key:generate
```

#### 5. Configure o banco de dados

**Opção 1: MySQL/MariaDB** (Produção)
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=suspront
DB_USERNAME=root
DB_PASSWORD=sua_senha
```

**Opção 2: SQLite** (Desenvolvimento)
```env
DB_CONNECTION=sqlite
# Comentar as outras variáveis DB_*
```

Se usar SQLite, crie o arquivo do banco:
```bash
touch database/database.sqlite
```

#### 6. Execute as migrações
```bash
php artisan migrate
```

#### 7. (Opcional) Popule o banco com dados de teste
```bash
php artisan db:seed
```

#### 8. Configure o storage para uploads
```bash
php artisan storage:link
```

#### 9. Inicie o ambiente de desenvolvimento

**Opção 1: Comando único** (recomendado - inicia servidor, fila, logs e Vite)
```bash
composer dev
```

**Opção 2: Comandos separados**

Em terminais diferentes, execute:
```bash
# Terminal 1: Servidor Laravel
php artisan serve

# Terminal 2: Fila de jobs
php artisan queue:listen

# Terminal 3: Vite (compilação de assets)
npm run dev
```

O sistema estará disponível em **http://localhost:8000**

### Instalação com Docker (Laravel Sail)

Para usar Docker com Laravel Sail:

```bash
# Instalar dependências via Docker
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install

# Iniciar os containers
./vendor/bin/sail up -d

# Executar migrações
./vendor/bin/sail artisan migrate

# Executar seeders (opcional)
./vendor/bin/sail artisan db:seed
```

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

### Arquitetura do Sistema

O SusPront utiliza uma arquitetura moderna baseada em **Laravel 12** com componentes **Livewire** para interatividade em tempo real.

### Módulos do Sistema

O sistema está organizado nos seguintes módulos principais:

1. **Pacientes** - Gerenciamento de cadastros de pacientes
2. **Médicos** - Gerenciamento do corpo clínico e plantões
3. **Atendentes** - Gestão do pessoal administrativo
4. **Consultas** - Agendamento, remarcação e registro de atendimentos
5. **Prontuários** - Histórico médico e evoluções dos pacientes
6. **Exames** - Solicitações, agendamento e resultados
7. **Receitas** - Emissão e impressão de receitas médicas
8. **Notificações** - Sistema de alertas e notificações em tempo real

### Estrutura de Diretórios

```
tcc_suspront/
├── app/
│   ├── Http/
│   │   └── Controllers/       # Controladores tradicionais
│   ├── Livewire/              # Componentes Livewire reativos
│   │   ├── Atendentes/
│   │   ├── Consultas/
│   │   ├── Dashboard/
│   │   ├── Exames/
│   │   ├── Medicos/
│   │   ├── Notificacoes/
│   │   ├── Pacientes/
│   │   ├── Prontuarios/
│   │   └── Receitas/
│   └── Models/                # Modelos Eloquent
├── database/
│   ├── migrations/            # Migrações do banco de dados
│   ├── seeders/               # Seeders para dados de teste
│   └── factories/             # Factories para testes
├── docs/                      # Documentação do projeto
│   ├── api-documentation.md   # Documentação das APIs
│   └── css-*.md               # Documentação de estilos
├── public/                    # Arquivos públicos
│   ├── css/                   # Estilos customizados
│   ├── js/                    # Scripts JavaScript
│   └── img/                   # Imagens e assets
├── resources/
│   ├── views/                 # Views Blade organizadas por módulo
│   │   ├── livewire/          # Views dos componentes Livewire
│   │   ├── layouts/           # Layouts principais
│   │   └── [módulos]/         # Views de cada módulo
│   └── js/                    # JavaScript (Alpine.js, etc.)
├── routes/
│   ├── web.php                # Rotas web principais
│   └── api.php                # Rotas de API (se houver)
├── storage/
│   └── app/public/            # Uploads (resultados de exames, etc.)
└── tests/
    └── Feature/               # Testes de funcionalidades
        └── Livewire/          # Testes dos componentes Livewire
```

### Componentes Livewire

O sistema utiliza **Livewire 3.6** para criar interfaces interativas sem JavaScript complexo:

- **Dashboard Components**: Cards estatísticos, gráficos, notificações
- **CRUD Components**: Listas com filtros, formulários reativos
- **Real-time Updates**: Atualização automática de dados
- **Notifications**: Badge de notificações não lidas

### Padrões de Código

- **PSR-12**: Padrão de código PHP
- **Laravel Best Practices**: Seguindo as melhores práticas do framework
- **Livewire Conventions**: Padrões do Livewire para componentes reativos

## Testes

O projeto possui uma suíte de testes automatizados para garantir a qualidade do código.

### Executar Testes

```bash
# Executar todos os testes
php artisan test

# Ou usando composer
composer test

# Executar com coverage (se configurado)
php artisan test --coverage
```

### Estrutura de Testes

- **Feature Tests**: Testes de funcionalidades completas
  - Testes de Controllers
  - Testes de Componentes Livewire
  - Testes de APIs
  - Testes de Integração

### Testes Implementados

✅ Dashboard Controller  
✅ Pacientes (Controller e Livewire)  
✅ Médicos (Controller e Livewire)  
✅ Atendentes (Controller e Livewire)  
✅ Consultas (Controller e Livewire)  
✅ Prontuários (Livewire)  
✅ Exames (Controller e Livewire)  
✅ Receitas (Controller e Livewire)  
✅ Notificações (Controller e Badge Livewire)  
✅ APIs de Consultas  

## Documentação

### Documentação Disponível

- **[API Documentation](docs/api-documentation.md)** - Documentação completa das APIs REST
- **[CSS Structure](docs/css-structure.md)** - Organização dos estilos CSS
- **[CSS Organization](docs/css-organization-final.md)** - Estrutura final do CSS

### Acessando a Documentação no Sistema

Usuários administradores podem acessar a documentação da API através do menu do sistema em:
```
/api/doc
```

## Comandos Úteis

### Desenvolvimento

```bash
# Iniciar todos os serviços (servidor, fila, logs, vite)
composer dev

# Limpar cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Executar migrações
php artisan migrate

# Rollback última migração
php artisan migrate:rollback

# Recriar banco de dados (cuidado!)
php artisan migrate:fresh --seed

# Formatar código (Laravel Pint)
./vendor/bin/pint

# Ver logs em tempo real
php artisan pail
```

### Produção

```bash
# Compilar assets para produção
npm run build

# Otimizar aplicação
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Equipe

Este projeto foi desenvolvido como Trabalho de Conclusão de Curso (TCC) por:

- **Gabriel Ângelo Oliveira Silva** - *Desenvolvedor* - [GitHub](https://github.com/DevAngeloOliveira)
- **Orientador: Thiago Rodrigues** - *Professor* - Centro Universitário de João Pessoa

## Contribuindo

Contribuições são bem-vindas! Por favor, siga estes passos:

1. Fork o projeto
2. Crie uma branch para sua feature (`git checkout -b feature/MinhaFeature`)
3. Commit suas mudanças (`git commit -m 'Adiciona MinhaFeature'`)
4. Push para a branch (`git push origin feature/MinhaFeature`)
5. Abra um Pull Request

## Licença

Este projeto está licenciado sob a [Licença MIT](LICENSE) - veja o arquivo LICENSE para mais detalhes.

---

<p align="center">
  Desenvolvido com ❤️ para melhorar o sistema de saúde pública do Brasil
</p>

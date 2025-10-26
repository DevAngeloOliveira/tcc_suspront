# SusPront - Estrutura Modular de CSS

Este documento descreve a nova estrutura modular de estilos do SusPront, organizando os arquivos CSS por funcionalidade e responsabilidade.

## 📁 Estrutura de Diretórios

```
resources/css/
├── app.css                    # Arquivo principal que importa todos os módulos
├── base/
│   └── variables.css          # Variáveis CSS globais e configurações base
├── layouts/
│   └── modern.css            # Estilos do layout principal (sidebar, navbar)
├── components/
│   ├── dashboard.css         # Componentes específicos do dashboard
│   └── common.css           # Componentes reutilizáveis (modais, tooltips, etc.)
├── modules/
│   ├── pacientes.css        # Estilos específicos do módulo pacientes
│   ├── consultas.css        # Estilos específicos do módulo consultas
│   └── exames.css           # Estilos específicos do módulo exames
└── pages/
    ├── details.css          # Estilos para páginas de detalhes
    └── auth.css            # Estilos para páginas de autenticação
```

## 🎯 Propósito de Cada Arquivo

### `base/variables.css`
- **Propósito**: Variáveis CSS globais, cores do sistema, medidas, sombras, z-index
- **Conteúdo**: Paleta de cores médicas, dimensões da sidebar, configurações de transição
- **Uso**: Importado primeiro para disponibilizar variáveis em todos os outros arquivos

### `layouts/modern.css`
- **Propósito**: Layout principal da aplicação (sidebar, navbar, estrutura responsiva)
- **Conteúdo**: Sidebar responsiva, navbar superior, overlay mobile, transições
- **Uso**: Layout base para todas as páginas internas do sistema

### `components/dashboard.css`
- **Propósito**: Componentes específicos do dashboard médico
- **Conteúdo**: Cards estatísticos, gráficos, notificações, ações rápidas
- **Uso**: Apenas nas páginas de dashboard

### `components/common.css`
- **Propósito**: Componentes reutilizáveis em todo o sistema
- **Conteúdo**: Modais, tooltips, loading states, dropdowns, pagination
- **Uso**: Disponível globalmente para qualquer página

### `modules/pacientes.css`
- **Propósito**: Estilos específicos para o módulo de pacientes
- **Conteúdo**: Cards de pacientes, formulários, detalhes, histórico médico
- **Uso**: Páginas relacionadas ao módulo pacientes

### `modules/consultas.css`
- **Propósito**: Estilos específicos para o módulo de consultas
- **Conteúdo**: Cards de consultas, agendamento, status, evolução médica
- **Uso**: Páginas relacionadas ao módulo consultas

### `modules/exames.css`
- **Propósito**: Estilos específicos para o módulo de exames
- **Conteúdo**: Cards de exames, resultados, anexos, tipos de exame
- **Uso**: Páginas relacionadas ao módulo exames

### `pages/details.css`
- **Propósito**: Estilos para páginas de detalhes e visualização
- **Conteúdo**: Layout de detalhes, tabs, timeline, ações rápidas
- **Uso**: Páginas de detalhes de qualquer módulo

### `pages/auth.css`
- **Propósito**: Estilos para páginas de autenticação
- **Conteúdo**: Login, registro, recuperação de senha
- **Uso**: Páginas de autenticação

## 🚀 Como Usar

### 1. Importação Global
O arquivo `app.css` já importa todos os módulos. Basta usar:

```blade
@vite(['resources/css/app.css', 'resources/js/app.js'])
```

### 2. Estilos Específicos por Página
Para adicionar estilos específicos a uma página:

```blade
@push('styles')
<style>
    /* Estilos específicos desta página */
    .meu-componente-especifico {
        /* ... */
    }
</style>
@endpush
```

### 3. Classes Disponíveis

#### Dashboard
- `.stat-card` - Cards de estatísticas
- `.content-card` - Cards de conteúdo
- `.notification-list` - Lista de notificações
- `.quick-actions` - Ações rápidas

#### Pacientes
- `.paciente-card` - Card de paciente
- `.paciente-form` - Formulário de paciente
- `.paciente-details` - Detalhes do paciente

#### Consultas
- `.consulta-card` - Card de consulta
- `.consulta-form` - Formulário de consulta
- `.status-consulta` - Badge de status

#### Exames
- `.exame-card` - Card de exame
- `.resultado-item` - Item de resultado
- `.anexos-grid` - Grid de anexos

#### Componentes Comuns
- `.modal-custom` - Modal customizado
- `.toast-custom` - Notificação toast
- `.search-box` - Caixa de pesquisa
- `.filters-panel` - Painel de filtros

## 🎨 Sistema de Cores

### Cores Principais
- `--medical-primary`: #0891b2 (Azul médico principal)
- `--medical-secondary`: #06b6d4 (Azul secundário)
- `--medical-success`: #059669 (Verde de sucesso)
- `--medical-warning`: #d97706 (Laranja de aviso)
- `--medical-danger`: #dc2626 (Vermelho de perigo)

### Cores SUS
- `--sus-blue`: #2F74B5 (Azul SUS)
- `--sus-green`: #19883F (Verde SUS)
- `--sus-yellow`: #FFCC29 (Amarelo SUS)

## 📱 Responsividade

Todos os módulos incluem breakpoints responsivos:
- **Mobile**: < 576px
- **Tablet**: 576px - 768px
- **Desktop**: > 768px

## 🔧 Manutenção

### Adicionando um Novo Módulo
1. Crie o arquivo `modules/novo-modulo.css`
2. Adicione a importação em `app.css`:
   ```css
   @import './modules/novo-modulo.css';
   ```

### Adicionando uma Nova Página
1. Crie o arquivo `pages/nova-pagina.css`
2. Adicione a importação em `app.css`:
   ```css
   @import './pages/nova-pagina.css';
   ```

### Modificando Variáveis
- Edite apenas o arquivo `base/variables.css`
- As mudanças se propagarão automaticamente para todos os módulos

## ⚡ Performance

### Benefícios da Estrutura Modular:
- **Manutenibilidade**: Cada arquivo tem responsabilidade específica
- **Reutilização**: Componentes comuns centralizados
- **Organização**: Estrutura clara e previsível
- **Escalabilidade**: Fácil adição de novos módulos
- **Cache**: Navegadores podem cachear módulos separadamente

### Build e Compilation:
- O Vite automaticamente processa e minifica todos os arquivos
- Tree-shaking remove estilos não utilizados em produção
- Source maps disponíveis em desenvolvimento

## 📝 Convenções de Nomenclatura

### Classes CSS:
- **Módulos**: `.paciente-card`, `.consulta-form`
- **Estados**: `.status-ativo`, `.status-pendente`
- **Variações**: `.card.primary`, `.btn.success`
- **Componentes**: `.modal-custom`, `.toast-custom`

### Variáveis CSS:
- **Cores**: `--medical-primary`, `--sus-blue`
- **Medidas**: `--sidebar-width`, `--content-padding`
- **Sombras**: `--shadow-sm`, `--shadow-md`
- **Z-index**: `--z-navbar`, `--z-sidebar`

---

Esta estrutura modular facilita a manutenção, melhora a organização do código e permite um desenvolvimento mais eficiente do sistema SusPront.

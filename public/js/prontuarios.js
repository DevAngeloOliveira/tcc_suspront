/**
 * SusPront - Sistema de Prontuário Eletrônico para o SUS
 * Módulo de Prontuários - Funcionalidades JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    // Editor de texto para observações do prontuário
    inicializarEditorTexto();

    // Linha do tempo do paciente
    const timelineContainer = document.getElementById('timeline-prontuario');
    if (timelineContainer) {
        const pacienteId = timelineContainer.getAttribute('data-paciente-id');
        if (pacienteId) {
            carregarLinhaTempoPaciente(pacienteId);
        }
    }

    // Tabs para histórico médico
    const historicoTabs = document.querySelectorAll('button[data-bs-toggle="tab"]');
    if (historicoTabs.length > 0) {
        historicoTabs.forEach(function(tab) {
            tab.addEventListener('shown.bs.tab', function(e) {
                const target = e.target.getAttribute('data-bs-target');
                if (target === '#consultas-tab-pane') {
                    carregarConsultasPaciente();
                } else if (target === '#exames-tab-pane') {
                    carregarExamesPaciente();
                } else if (target === '#medicamentos-tab-pane') {
                    carregarMedicamentosPaciente();
                }
            });
        });
    }

    // Adicionar nova observação ao prontuário
    const formNovaObservacao = document.getElementById('form-nova-observacao');
    if (formNovaObservacao) {
        formNovaObservacao.addEventListener('submit', function(e) {
            e.preventDefault();

            const prontuarioId = this.getAttribute('data-prontuario-id');
            const observacao = document.getElementById('nova-observacao').value;

            if (observacao.trim() === '') {
                alert('Por favor, digite uma observação.');
                return;
            }

            adicionarObservacaoProntuario(prontuarioId, observacao);
        });
    }
});

/**
 * Inicializa editor de texto para observações
 */
function inicializarEditorTexto() {
    const textareas = document.querySelectorAll('.editor-texto');
    if (textareas.length > 0 && typeof ClassicEditor !== 'undefined') {
        textareas.forEach(function(textarea) {
            ClassicEditor
                .create(textarea, {
                    toolbar: ['heading', '|', 'bold', 'italic', 'bulletedList', 'numberedList', '|', 'undo', 'redo'],
                    language: 'pt-br'
                })
                .catch(error => console.error(error));
        });
    }
}

/**
 * Carrega linha do tempo do paciente
 */
function carregarLinhaTempoPaciente(pacienteId) {
    fetch(`/api/pacientes/${pacienteId}/linha-tempo`)
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('timeline-prontuario');
            container.innerHTML = '';

            if (data.length === 0) {
                container.innerHTML = '<div class="alert alert-info">Nenhum registro encontrado para este paciente.</div>';
                return;
            }

            // Criar elementos da linha do tempo
            const timeline = document.createElement('div');
            timeline.className = 'timeline';

            data.forEach((item, index) => {
                const timelineItem = document.createElement('div');
                timelineItem.className = 'timeline-item';

                // Ícone conforme o tipo de registro
                let iconClass = '';
                switch (item.tipo) {
                    case 'consulta':
                        iconClass = 'fa-stethoscope';
                        break;
                    case 'exame':
                        iconClass = 'fa-flask';
                        break;
                    case 'medicamento':
                        iconClass = 'fa-pills';
                        break;
                    case 'observacao':
                        iconClass = 'fa-clipboard';
                        break;
                    default:
                        iconClass = 'fa-calendar';
                }

                // Estrutura do item da linha do tempo
                timelineItem.innerHTML = `
                    <div class="timeline-icon bg-${getStatusColor(item.status)}">
                        <i class="fas ${iconClass}"></i>
                    </div>
                    <div class="timeline-date">${formatarData(item.data)}</div>
                    <div class="timeline-content">
                        <h5>${item.titulo}</h5>
                        <p>${item.descricao}</p>
                        ${item.link ? `<a href="${item.link}" class="btn btn-sm btn-primary">Ver detalhes</a>` : ''}
                    </div>
                `;

                timeline.appendChild(timelineItem);
            });

            container.appendChild(timeline);
        })
        .catch(error => console.error('Erro ao carregar linha do tempo:', error));
}

/**
 * Adiciona uma nova observação ao prontuário
 */
function adicionarObservacaoProntuario(prontuarioId, observacao) {
    // Criar token CSRF para requisição
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch(`/prontuarios/${prontuarioId}/observacao`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ observacao: observacao })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Limpar campo de observação
            document.getElementById('nova-observacao').value = '';

            // Adicionar a nova observação à lista
            const observacoesList = document.getElementById('observacoes-list');
            if (observacoesList) {
                const novaObservacao = document.createElement('div');
                novaObservacao.className = 'card mb-3';
                novaObservacao.innerHTML = `
                    <div class="card-body">
                        <h6 class="card-subtitle mb-2 text-muted">${data.data} - ${data.usuario}</h6>
                        <p class="card-text">${data.observacao}</p>
                    </div>
                `;

                observacoesList.prepend(novaObservacao);
            }

            // Notificar sucesso
            alert('Observação adicionada com sucesso!');
        } else {
            alert('Erro ao adicionar observação: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erro ao adicionar observação:', error);
        alert('Erro ao adicionar observação. Tente novamente mais tarde.');
    });
}

/**
 * Carrega histórico de consultas do paciente
 */
function carregarConsultasPaciente() {
    const container = document.getElementById('consultas-tab-pane');
    const pacienteId = container.getAttribute('data-paciente-id');

    if (!pacienteId) return;

    fetchWithCsrf(`/api/pacientes/${pacienteId}/consultas`)
        .then(response => response.json())
        .then(data => {
            const tabela = document.getElementById('tabela-consultas');
            if (!tabela) return;

            const tbody = tabela.querySelector('tbody');
            tbody.innerHTML = '';

            if (data.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center">Nenhuma consulta registrada</td>
                    </tr>
                `;
                return;
            }

            data.forEach(consulta => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${formatarData(consulta.data_hora)}</td>
                    <td>${consulta.medico.nome}</td>
                    <td>${consulta.medico.especialidade}</td>
                    <td>${consulta.status}</td>
                    <td>
                        <a href="/consultas/${consulta.id}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i> Detalhes
                        </a>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        })
        .catch(error => console.error('Erro ao carregar consultas:', error));
}

/**
 * Carrega histórico de exames do paciente
 */
function carregarExamesPaciente() {
    const container = document.getElementById('exames-tab-pane');
    const pacienteId = container.getAttribute('data-paciente-id');

    if (!pacienteId) return;

    fetch(`/api/pacientes/${pacienteId}/exames`)
        .then(response => response.json())
        .then(data => {
            const tabela = document.getElementById('tabela-exames');
            if (!tabela) return;

            const tbody = tabela.querySelector('tbody');
            tbody.innerHTML = '';

            if (data.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center">Nenhum exame registrado</td>
                    </tr>
                `;
                return;
            }

            data.forEach(exame => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${exame.tipo}</td>
                    <td>${formatarData(exame.data_solicitacao)}</td>
                    <td>${exame.status}</td>
                    <td>${exame.resultado ? 'Sim' : 'Não'}</td>
                    <td>
                        <a href="/exames/${exame.id}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i> Detalhes
                        </a>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        })
        .catch(error => console.error('Erro ao carregar exames:', error));
}

// Funções utilitárias
function formatarData(dataString) {
    const data = new Date(dataString);
    return data.toLocaleDateString('pt-BR') + ' ' + data.toLocaleTimeString('pt-BR', {hour: '2-digit', minute:'2-digit'});
}

function getStatusColor(status) {
    switch (status.toLowerCase()) {
        case 'concluido':
        case 'concluida':
        case 'realizado':
            return 'success';
        case 'agendado':
        case 'agendada':
            return 'primary';
        case 'em_andamento':
            return 'warning';
        case 'cancelado':
        case 'cancelada':
            return 'danger';
        default:
            return 'secondary';
    }
}

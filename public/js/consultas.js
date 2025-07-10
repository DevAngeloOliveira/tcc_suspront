/**
 * SusPront - Sistema de Prontuário Eletrônico para o SUS
 * Módulo de Consultas - Funcionalidades JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    // Calendário de agendamento de consultas
    const calendarioConsulta = document.getElementById('calendarioConsulta');
    if (calendarioConsulta) {
        inicializarCalendario();
    }

    // Seletor de médico por especialidade
    const especialidadeSelect = document.getElementById('especialidade');
    if (especialidadeSelect) {
        especialidadeSelect.addEventListener('change', function() {
            carregarMedicosPorEspecialidade(this, 'medico_id');
        });
    }

    // Verificação de horários disponíveis
    const dataConsultaInput = document.getElementById('data_consulta');
    const medicoSelect = document.getElementById('medico_id');

    if (dataConsultaInput && medicoSelect) {
        const verificarHorarios = function() {
            const data = dataConsultaInput.value;
            const medicoId = medicoSelect.value;

            if (data && medicoId) {
                carregarHorariosDisponiveis(data, medicoId);
            }
        };

        dataConsultaInput.addEventListener('change', verificarHorarios);
        medicoSelect.addEventListener('change', verificarHorarios);
    }

    // Botão de conclusão de consulta
    const btnConcluirConsulta = document.querySelectorAll('.btn-concluir-consulta');
    if (btnConcluirConsulta.length > 0) {
        btnConcluirConsulta.forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const consultaId = this.getAttribute('data-consulta-id');
                const form = document.getElementById('form-concluir-consulta-' + consultaId);

                // Verificar se há observações
                const observacoes = document.getElementById('observacoes-consulta-' + consultaId);
                if (observacoes && observacoes.value.trim() === '') {
                    if (!confirm('Não foram adicionadas observações para esta consulta. Deseja concluí-la mesmo assim?')) {
                        return;
                    }
                }

                if (form) {
                    form.submit();
                }
            });
        });
    }
});

/**
 * Inicializar calendário de consultas
 */
function inicializarCalendario() {
    // Requisitos: incluir biblioteca FullCalendar na página
    if (typeof FullCalendar === 'undefined') {
        console.warn('FullCalendar não está disponível. Inclua a biblioteca para usar o calendário.');
        return;
    }

    const calendarEl = document.getElementById('calendarioConsulta');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        locale: 'pt-br',
        slotMinTime: '07:00:00',
        slotMaxTime: '19:00:00',
        allDaySlot: false,
        height: 'auto',
        events: {
            url: '/api/consultas',
            method: 'GET',
            extraParams: {
                _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        },
        eventClick: function(info) {
            exibirDetalhesConsulta(info.event.id);
        },
        dateClick: function(info) {
            agendarNovaConsulta(info.dateStr);
        }
    });

    calendar.render();
}

/**
 * Exibir detalhes da consulta em modal
 */
function exibirDetalhesConsulta(consultaId) {
    fetchWithCsrf(`/api/consultas/${consultaId}`)
        .then(response => response.json())
        .then(data => {
            // Preencher modal com detalhes da consulta
            document.getElementById('detalhesConsultaPaciente').textContent = data.paciente.nome;
            document.getElementById('detalhesConsultaMedico').textContent = data.medico.nome;
            document.getElementById('detalhesConsultaData').textContent = new Date(data.data_hora).toLocaleString('pt-BR');
            document.getElementById('detalhesConsultaStatus').textContent = data.status;

            // Exibir modal
            const detalhesModal = new bootstrap.Modal(document.getElementById('modalDetalhesConsulta'));
            detalhesModal.show();
        })
        .catch(error => console.error('Erro ao carregar detalhes da consulta:', error));
}

/**
 * Agendar nova consulta
 */
function agendarNovaConsulta(data) {
    // Preencher o campo de data no formulário
    const inputData = document.getElementById('data_consulta');
    if (inputData) {
        inputData.value = data.split('T')[0]; // Formato YYYY-MM-DD
    }

    // Se houver hora, preencher o campo de hora
    if (data.includes('T')) {
        const hora = data.split('T')[1].substring(0, 5); // Formato HH:MM
        const inputHora = document.getElementById('hora_consulta');
        if (inputHora) {
            inputHora.value = hora;
        }
    }

    // Exibir modal de agendamento
    const agendamentoModal = new bootstrap.Modal(document.getElementById('modalAgendarConsulta'));
    agendamentoModal.show();
}

/**
 * Carregar horários disponíveis para o médico na data selecionada
 */
function carregarHorariosDisponiveis(data, medicoId) {
    const horariosSelect = document.getElementById('hora_consulta');

    // Limpar select de horários
    if (horariosSelect) {
        horariosSelect.innerHTML = '<option value="">Selecione um horário</option>';

        // Fazer requisição AJAX para buscar horários disponíveis
        fetchWithCsrf(`/api/consultas/horarios-disponiveis?data=${data}&medico_id=${medicoId}`)
            .then(response => response.json())
            .then(data => {
                data.horarios.forEach(horario => {
                    const option = document.createElement('option');
                    option.value = horario;
                    option.textContent = horario;
                    horariosSelect.appendChild(option);
                });

                if (data.horarios.length === 0) {
                    const option = document.createElement('option');
                    option.value = "";
                    option.textContent = "Nenhum horário disponível";
                    horariosSelect.appendChild(option);
                }
            })
            .catch(error => console.error('Erro ao carregar horários:', error));
    }
}

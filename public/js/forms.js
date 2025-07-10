/**
 * SusPront - Sistema de Prontuário Eletrônico para o SUS
 * Validação de formulários e funcionalidades dinâmicas
 */

document.addEventListener('DOMContentLoaded', function() {
    // Aplicar máscara aos campos CPF
    const cpfInputs = document.querySelectorAll('input[name="cpf"]');
    if (cpfInputs.length > 0) {
        cpfInputs.forEach(function(input) {
            input.addEventListener('input', function() {
                let value = this.value.replace(/\D/g, '');
                if (value.length <= 11) {
                    value = value.replace(/(\d{3})(\d)/, '$1.$2');
                    value = value.replace(/(\d{3})(\d)/, '$1.$2');
                    value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
                }
                this.value = value;
            });
        });
    }

    // Aplicar máscara aos campos telefone
    const telefoneInputs = document.querySelectorAll('input[name="telefone"]');
    if (telefoneInputs.length > 0) {
        telefoneInputs.forEach(function(input) {
            input.addEventListener('input', function() {
                let value = this.value.replace(/\D/g, '');
                if (value.length <= 11) {
                    if (value.length > 2) {
                        value = '(' + value.substring(0, 2) + ') ' + value.substring(2);
                    }
                    if (value.length > 9) {
                        value = value.substring(0, 10) + '-' + value.substring(10);
                    }
                }
                this.value = value;
            });
        });
    }

    // Aplicar máscara ao campo Cartão SUS
    const susInputs = document.querySelectorAll('input[name="cartao_sus"]');
    if (susInputs.length > 0) {
        susInputs.forEach(function(input) {
            input.addEventListener('input', function() {
                let value = this.value.replace(/\D/g, '');
                if (value.length <= 15) {
                    value = value.replace(/(\d{3})(\d)/, '$1 $2');
                    value = value.replace(/(\d{4})(\d)/, '$1 $2');
                    value = value.replace(/(\d{4})(\d)/, '$1 $2');
                }
                this.value = value;
            });
        });
    }

    // Validação de formulários
    const forms = document.querySelectorAll('.needs-validation');
    if (forms.length > 0) {
        forms.forEach(function(form) {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            });
        });
    }

    // Ativar tooltips do Bootstrap
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    if (tooltipTriggerList.length > 0) {
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    }

    // Ativar popovers do Bootstrap
    const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]');
    if (popoverTriggerList.length > 0) {
        const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl));
    }

    // Confirmação de exclusão
    const deleteButtons = document.querySelectorAll('.btn-delete');
    if (deleteButtons.length > 0) {
        deleteButtons.forEach(function(button) {
            button.addEventListener('click', function(event) {
                if (!confirm('Tem certeza que deseja excluir este item?')) {
                    event.preventDefault();
                }
            });
        });
    }

    // Filtros dinâmicos para tabelas
    setupTableFilters();
});

/**
 * Configura filtros dinâmicos para tabelas
 */
function setupTableFilters() {
    const tableFilters = document.querySelectorAll('.table-filter');
    if (tableFilters.length > 0) {
        tableFilters.forEach(function(input) {
            input.addEventListener('input', function() {
                const tableId = this.getAttribute('data-table');
                const table = document.getElementById(tableId);
                if (table) {
                    const rows = table.querySelectorAll('tbody tr');
                    const searchText = this.value.toLowerCase();

                    rows.forEach(function(row) {
                        const text = row.textContent.toLowerCase();
                        if (text.includes(searchText)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                }
            });
        });
    }
}

/**
 * Validação de CPF
 */
function validaCPF(cpf) {
    cpf = cpf.replace(/[^\d]+/g,'');
    if (cpf.length !== 11 ||
        cpf === "00000000000" ||
        cpf === "11111111111" ||
        cpf === "22222222222" ||
        cpf === "33333333333" ||
        cpf === "44444444444" ||
        cpf === "55555555555" ||
        cpf === "66666666666" ||
        cpf === "77777777777" ||
        cpf === "88888888888" ||
        cpf === "99999999999") {
        return false;
    }

    // Valida 1o dígito
    let add = 0;
    for (let i = 0; i < 9; i++) {
        add += parseInt(cpf.charAt(i)) * (10 - i);
    }
    let rev = 11 - (add % 11);
    if (rev === 10 || rev === 11) {
        rev = 0;
    }
    if (rev !== parseInt(cpf.charAt(9))) {
        return false;
    }

    // Valida 2o dígito
    add = 0;
    for (let i = 0; i < 10; i++) {
        add += parseInt(cpf.charAt(i)) * (11 - i);
    }
    rev = 11 - (add % 11);
    if (rev === 10 || rev === 11) {
        rev = 0;
    }
    if (rev !== parseInt(cpf.charAt(10))) {
        return false;
    }

    return true;
}

/**
 * Função para carregar médicos por especialidade
 */
function carregarMedicosPorEspecialidade(especialidadeSelect, medicoSelect) {
    const especialidade = especialidadeSelect.value;
    const medicosSelect = document.getElementById(medicoSelect);

    // Limpar select de médicos
    medicosSelect.innerHTML = '<option value="">Selecione um médico</option>';

    if (especialidade) {
        // Fazer requisição AJAX para buscar médicos por especialidade
        fetchWithCsrf(`/api/medicos/especialidade/${especialidade}`)
            .then(response => response.json())
            .then(data => {
                data.forEach(medico => {
                    const option = document.createElement('option');
                    option.value = medico.id;
                    option.textContent = medico.nome;
                    medicosSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Erro ao carregar médicos:', error));
    }
}

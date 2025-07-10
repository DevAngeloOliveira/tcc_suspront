/**
 * SusPront - Sistema de Prontuário Eletrônico para o SUS
 * Módulo de Exames - Funcionalidades JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    // Preview de upload de resultados de exames
    const fileInputs = document.querySelectorAll('.custom-file-input');
    if (fileInputs.length > 0) {
        fileInputs.forEach(function(input) {
            input.addEventListener('change', function(e) {
                const fileName = e.target.files[0]?.name || 'Nenhum arquivo selecionado';
                const label = this.nextElementSibling;
                if (label) {
                    label.innerHTML = fileName;
                }

                // Preview de imagem, se for uma imagem
                const preview = document.getElementById(this.getAttribute('data-preview-id'));
                if (preview && e.target.files[0]) {
                    const file = e.target.files[0];
                    if (file.type.match('image.*')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            preview.src = e.target.result;
                            preview.style.display = 'block';
                        };
                        reader.readAsDataURL(file);
                    } else if (file.type === 'application/pdf') {
                        preview.src = '/img/pdf-icon.png'; // Ícone para PDF
                        preview.style.display = 'block';
                    } else {
                        preview.src = '/img/file-icon.png'; // Ícone genérico
                        preview.style.display = 'block';
                    }
                }
            });
        });
    }

    // Modal para visualizar resultados de exames
    const btnVisualizarExame = document.querySelectorAll('.btn-visualizar-exame');
    if (btnVisualizarExame.length > 0) {
        btnVisualizarExame.forEach(function(btn) {
            btn.addEventListener('click', function() {
                const exameId = this.getAttribute('data-exame-id');
                carregarDetalhesExame(exameId);
            });
        });
    }

    // Agendar exame
    const btnAgendarExame = document.querySelectorAll('.btn-agendar-exame');
    if (btnAgendarExame.length > 0) {
        btnAgendarExame.forEach(function(btn) {
            btn.addEventListener('click', function() {
                const exameId = this.getAttribute('data-exame-id');
                document.getElementById('exame_id_agendamento').value = exameId;

                // Buscar detalhes do exame para exibir no modal
                fetch(`/api/exames/${exameId}`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('agendarExameModalLabel').textContent =
                            `Agendar ${data.tipo} para ${data.paciente.nome}`;
                    })
                    .catch(error => console.error('Erro ao carregar detalhes do exame:', error));

                const agendarModal = new bootstrap.Modal(document.getElementById('modalAgendarExame'));
                agendarModal.show();
            });
        });
    }
});

/**
 * Carrega detalhes de um exame para visualização
 */
function carregarDetalhesExame(exameId) {
    fetch(`/api/exames/${exameId}`)
        .then(response => response.json())
        .then(data => {
            // Preencher modal com detalhes do exame
            document.getElementById('detalhesExamePaciente').textContent = data.paciente.nome;
            document.getElementById('detalhesExameTipo').textContent = data.tipo;
            document.getElementById('detalhesExameStatus').textContent = data.status;
            document.getElementById('detalhesExameData').textContent =
                data.data_realizacao ? new Date(data.data_realizacao).toLocaleDateString('pt-BR') : 'Não realizado';

            // Se houver resultado, exibir
            const resultadoContainer = document.getElementById('detalhesExameResultado');
            resultadoContainer.innerHTML = '';

            if (data.resultado_url) {
                if (data.resultado_url.match(/\.(jpeg|jpg|gif|png)$/i)) {
                    // Se for imagem
                    const img = document.createElement('img');
                    img.src = data.resultado_url;
                    img.className = 'img-fluid';
                    resultadoContainer.appendChild(img);
                } else if (data.resultado_url.match(/\.pdf$/i)) {
                    // Se for PDF
                    const embed = document.createElement('embed');
                    embed.src = data.resultado_url;
                    embed.width = '100%';
                    embed.height = '500px';
                    embed.type = 'application/pdf';
                    resultadoContainer.appendChild(embed);
                } else {
                    // Outros arquivos
                    const link = document.createElement('a');
                    link.href = data.resultado_url;
                    link.textContent = 'Baixar resultado';
                    link.target = '_blank';
                    link.className = 'btn btn-primary';
                    resultadoContainer.appendChild(link);
                }
            } else {
                resultadoContainer.textContent = 'Nenhum resultado disponível.';
            }

            // Exibir modal
            const detalhesModal = new bootstrap.Modal(document.getElementById('modalDetalhesExame'));
            detalhesModal.show();
        })
        .catch(error => console.error('Erro ao carregar detalhes do exame:', error));
}

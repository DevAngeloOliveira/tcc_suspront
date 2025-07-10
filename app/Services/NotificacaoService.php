<?php

namespace App\Services;

use App\Models\Notificacao;
use App\Models\User;
use App\Models\Medico;
use App\Models\Consulta;
use Carbon\Carbon;

class NotificacaoService
{
    /**
     * Criar nova notificação
     *
     * @param int $userId ID do usuário destinatário
     * @param string $tipo Tipo da notificação
     * @param string $titulo Título da notificação
     * @param string $mensagem Conteúdo da notificação
     * @param array $dadosExtras Dados adicionais para a notificação
     * @return Notificacao
     */
    public function criarNotificacao($userId, $tipo, $titulo, $mensagem, $dadosExtras = [])
    {
        return Notificacao::create([
            'user_id' => $userId,
            'tipo' => $tipo,
            'titulo' => $titulo,
            'mensagem' => $mensagem,
            'dados_extras' => $dadosExtras,
            'lida' => false
        ]);
    }

    /**
     * Notificar o médico sobre uma nova consulta
     *
     * @param Consulta $consulta
     * @return Notificacao|null
     */
    public function notificarNovaConsulta(Consulta $consulta)
    {
        $medico = $consulta->medico;

        if (!$medico || !$medico->user) {
            return null;
        }

        $dataHora = Carbon::parse($consulta->data_hora)->format('d/m/Y \à\s H:i');
        $paciente = $consulta->paciente;

        return $this->criarNotificacao(
            $medico->user->id,
            'nova_consulta',
            'Nova consulta agendada',
            "Uma nova consulta foi agendada para $dataHora com o paciente {$paciente->nome}.",
            [
                'consulta_id' => $consulta->id,
                'paciente_id' => $paciente->id,
                'data_hora' => $consulta->data_hora
            ]
        );
    }

    /**
     * Notificar o médico sobre alteração em uma consulta
     *
     * @param Consulta $consulta
     * @return Notificacao|null
     */
    public function notificarAlteracaoConsulta(Consulta $consulta)
    {
        $medico = $consulta->medico;

        if (!$medico || !$medico->user) {
            return null;
        }

        $dataHora = Carbon::parse($consulta->data_hora)->format('d/m/Y \à\s H:i');
        $paciente = $consulta->paciente;

        return $this->criarNotificacao(
            $medico->user->id,
            'alteracao_consulta',
            'Consulta alterada',
            "Uma consulta foi alterada para $dataHora com o paciente {$paciente->nome}.",
            [
                'consulta_id' => $consulta->id,
                'paciente_id' => $paciente->id,
                'data_hora' => $consulta->data_hora
            ]
        );
    }

    /**
     * Notificar o médico sobre o cancelamento de uma consulta
     *
     * @param Consulta $consulta
     * @return Notificacao|null
     */
    public function notificarCancelamentoConsulta(Consulta $consulta)
    {
        $medico = $consulta->medico;

        if (!$medico || !$medico->user) {
            return null;
        }

        $dataHora = Carbon::parse($consulta->data_hora)->format('d/m/Y \à\s H:i');
        $paciente = $consulta->paciente;

        return $this->criarNotificacao(
            $medico->user->id,
            'cancelamento_consulta',
            'Consulta cancelada',
            "A consulta agendada para $dataHora com o paciente {$paciente->nome} foi cancelada.",
            [
                'consulta_id' => $consulta->id,
                'paciente_id' => $paciente->id,
                'data_hora' => $consulta->data_hora,
                'motivo_cancelamento' => $consulta->motivo_cancelamento
            ]
        );
    }

    /**
     * Notificar o médico sobre a confirmação de uma consulta
     *
     * @param Consulta $consulta
     * @return Notificacao|null
     */
    public function notificarConfirmacaoConsulta(Consulta $consulta)
    {
        $medico = $consulta->medico;

        if (!$medico || !$medico->user) {
            return null;
        }

        $dataHora = Carbon::parse($consulta->data_hora)->format('d/m/Y \à\s H:i');
        $paciente = $consulta->paciente;

        return $this->criarNotificacao(
            $medico->user->id,
            'confirmacao_consulta',
            'Consulta confirmada',
            "A consulta agendada para $dataHora com o paciente {$paciente->nome} foi confirmada.",
            [
                'consulta_id' => $consulta->id,
                'paciente_id' => $paciente->id,
                'data_hora' => $consulta->data_hora
            ]
        );
    }
}

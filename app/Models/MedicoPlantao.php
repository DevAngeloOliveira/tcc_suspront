<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class MedicoPlantao extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'medico_plantoes';

    protected $fillable = [
        'medico_id',
        'data_inicio',
        'data_fim',
        'hora_inicio',
        'hora_fim',
        'dia_semana',
        'recorrente',
        'status',
        'capacidade_consultas',
    ];

    protected $casts = [
        'data_inicio' => 'date',
        'data_fim' => 'date',
        'hora_inicio' => 'datetime',
        'hora_fim' => 'datetime',
        'recorrente' => 'boolean',
    ];

    /**
     * Relação com o médico
     */
    public function medico()
    {
        return $this->belongsTo(Medico::class);
    }

    /**
     * Relação com as consultas feitas neste plantão
     */
    public function consultas()
    {
        return $this->hasMany(Consulta::class, 'plantao_id');
    }

    /**
     * Verifica se a data e hora estão dentro deste plantão
     */
    public function contemDataHora($dataHora)
    {
        $data = Carbon::parse($dataHora)->format('Y-m-d');
        $hora = Carbon::parse($dataHora)->format('H:i:s');

        // Se for recorrente, verificar o dia da semana
        if ($this->recorrente) {
            $diaSemana = Carbon::parse($dataHora)->dayOfWeek;
            if ($diaSemana != $this->dia_semana) {
                return false;
            }

            // Verificar se a data está no intervalo de recorrência
            $dataNoIntervalo = Carbon::parse($data)->between(
                Carbon::parse($this->data_inicio),
                $this->data_fim ? Carbon::parse($this->data_fim) : Carbon::parse('2099-12-31')
            );

            if (!$dataNoIntervalo) {
                return false;
            }
        } else {
            // Verificar se a data está no intervalo do plantão
            $dataNoIntervalo = Carbon::parse($data)->between(
                Carbon::parse($this->data_inicio),
                $this->data_fim ? Carbon::parse($this->data_fim) : Carbon::parse($this->data_inicio)
            );

            if (!$dataNoIntervalo) {
                return false;
            }
        }

        // Verificar se a hora está no intervalo do plantão
        $horaInicio = Carbon::parse($this->hora_inicio)->format('H:i:s');
        $horaFim = Carbon::parse($this->hora_fim)->format('H:i:s');

        return $hora >= $horaInicio && $hora <= $horaFim;
    }

    /**
     * Retorna quantos slots de consulta ainda estão disponíveis neste plantão
     */
    public function slotsDisponiveis($data)
    {
        $consultas = $this->consultas()
            ->whereDate('data_hora', $data)
            ->count();

        return $this->capacidade_consultas - $consultas;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Consulta extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'paciente_id',
        'medico_id',
        'plantao_id',
        'prontuario_id',
        'data_hora',
        'tipo_consulta',
        'queixa_principal',
        'diagnostico',
        'prescricao',
        'observacoes',
        'status',
        'motivo_cancelamento',
        'cancelado_por',
        'notificado_em',
    ];

    protected $dates = ['data_hora', 'deleted_at'];

    /**
     * Obtém o paciente associado à consulta.
     */
    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    /**
     * Obtém o médico associado à consulta.
     */
    public function medico()
    {
        return $this->belongsTo(Medico::class);
    }

    /**
     * Obtém o prontuário associado à consulta.
     */
    public function prontuario()
    {
        return $this->belongsTo(Prontuario::class);
    }

    /**
     * Obtém as receitas associadas à consulta.
     */
    public function receitas()
    {
        return $this->hasMany(Receita::class);
    }

    /**
     * Obtém os exames associados à consulta.
     */
    public function exames()
    {
        return $this->hasMany(Exame::class);
    }

    /**
     * Obtém o plantão associado à consulta
     */
    public function plantao()
    {
        return $this->belongsTo(MedicoPlantao::class, 'plantao_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Prontuario extends Model
{
    use HasFactory;

    protected $fillable = [
        'paciente_id',
        'historico_medico',
        'medicamentos_atuais',
        'observacoes',
    ];

    /**
     * Obtém o paciente dono do prontuário.
     */
    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    /**
     * Obtém todas as consultas registradas neste prontuário.
     */
    public function consultas()
    {
        return $this->hasMany(Consulta::class);
    }

    /**
     * Obtém todas as evoluções registradas neste prontuário.
     */
    public function evolucoes()
    {
        return $this->hasMany(Evolucao::class);
    }

    /**
     * Obtém todas as receitas associadas a este prontuário
     */
    public function receitas()
    {
        return $this->hasMany(Receita::class);
    }
}

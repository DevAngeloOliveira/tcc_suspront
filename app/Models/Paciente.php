<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Paciente extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nome',
        'cpf',
        'rg',
        'cartao_sus',
        'data_nascimento',
        'sexo',
        'endereco',
        'telefone',
        'email',
        'alergias',
        'condicoes_preexistentes',
    ];

    protected $dates = ['data_nascimento', 'deleted_at'];

    /**
     * Obtém o prontuário do paciente.
     */
    public function prontuario()
    {
        return $this->hasOne(Prontuario::class);
    }

    /**
     * Obtém todas as consultas do paciente.
     */
    public function consultas()
    {
        return $this->hasMany(Consulta::class);
    }

    /**
     * Obtém todos os exames do paciente.
     */
    public function exames()
    {
        return $this->hasMany(Exame::class);
    }
}

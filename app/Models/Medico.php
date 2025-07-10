<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Medico extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nome',
        'crm',
        'especialidade',
        'cpf',
        'telefone',
        'email',
        'user_id',
    ];

    protected $dates = ['deleted_at'];

    /**
     * Obtém o usuário associado ao médico.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtém todas as consultas do médico.
     */
    public function consultas()
    {
        return $this->hasMany(Consulta::class);
    }

    /**
     * Obtém todos os exames solicitados pelo médico.
     */
    public function exames()
    {
        return $this->hasMany(Exame::class);
    }

    /**
     * Obtém todas as receitas emitidas pelo médico
     */
    public function receitas()
    {
        return $this->hasMany(Receita::class);
    }

    /**
     * Obtém todos os plantões do médico
     */
    public function plantoes()
    {
        return $this->hasMany(MedicoPlantao::class);
    }
}

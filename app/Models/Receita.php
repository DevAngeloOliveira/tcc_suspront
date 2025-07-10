<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Receita extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'prontuario_id',
        'medico_id',
        'consulta_id',
        'descricao',
        'medicamentos',
        'posologia',
        'observacoes',
        'validade',
    ];

    protected $casts = [
        'validade' => 'date',
    ];

    protected $dates = ['deleted_at'];

    /**
     * Obtém o prontuário relacionado à receita
     */
    public function prontuario()
    {
        return $this->belongsTo(Prontuario::class);
    }

    /**
     * Obtém o médico que emitiu a receita
     */
    public function medico()
    {
        return $this->belongsTo(Medico::class);
    }

    /**
     * Obtém a consulta relacionada à receita (se houver)
     */
    public function consulta()
    {
        return $this->belongsTo(Consulta::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Exame extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'paciente_id',
        'consulta_id',
        'medico_id',
        'tipo_exame',
        'descricao',
        'data_solicitacao',
        'data_realizacao',
        'resultado',
        'observacoes',
        'arquivo_resultado',
        'status',
    ];

    protected $dates = ['data_solicitacao', 'data_realizacao', 'deleted_at'];

    /**
     * Obtém o caminho completo do arquivo de resultado
     */
    public function getArquivoResultadoPathAttribute()
    {
        if ($this->arquivo_resultado) {
            return 'storage/' . $this->arquivo_resultado;
        }
        return null;
    }

    /**
     * Obtém o paciente associado ao exame.
     */
    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    /**
     * Obtém o médico que solicitou o exame.
     */
    public function medico()
    {
        return $this->belongsTo(Medico::class);
    }

    /**
     * Obtém a consulta associada ao exame.
     */
    public function consulta()
    {
        return $this->belongsTo(Consulta::class);
    }
}
